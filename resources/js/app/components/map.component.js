const { setActiveConstituency } = require('app/actions/filter');

class MapComponentController {

	constructor($scope, $ngRedux) {
		this.$scope = $scope;

		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);

	}

	mapStateToThis({ map: { width, height, parsedGeometry: geometry, extraGeometry }, filter }) {
		return {
			width,
			height,
			geometry,
			extraGeometry,
			filter
		}
	}

	mapDispatchToThis(dispatch) {
		return {
			setActive: (id) => dispatch(setActiveConstituency(id))
		}
	}

	onConstituencyClick(key) {
		this.setActive(key);
	}

	classObject(g) {
		return {
			'map__constituency--active': this.filter.activeConstituency === g.id  || this.filter.activeCounty === g.county_id || this.filter.activeParty === g.party_id,
			'map__constituency--foreign': !g.id
		}
	}

}

module.exports = {
	controller: [ '$scope', '$ngRedux', MapComponentController ],
	controllerAs: 'vm',
	template: [
		'<svg ng-attr-width="{{vm.width}}" ng-attr-height="{{vm.height}}" class="map__svg">',
			'<g class="map__group">',
				'<path ng-repeat="g in vm.geometry track by $index" ng-attr-d="{{ g.geometry }}" ng-style="{ color: g.properties.fill }" ng-click="g.id && vm.onConstituencyClick(g.id)" class="map__constituency" ng-class="vm.classObject(g)"></path>',
				'<path ng-repeat="g in vm.extraGeometry track by $index" ng-attr-d="{{ g.geometry }}" class="map__nonconstituency"></path>',
			'</g>',
		'</svg>'
	].join('')
}
