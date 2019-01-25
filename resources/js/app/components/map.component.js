const { setActiveConstituency } = require('app/actions/filter');

class MapComponentController {

	constructor($scope, $ngRedux) {
		this.$scope = $scope;

		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);

	}

	mapStateToThis({ map: { width, height, geometry }, filter }) {
		return {
			width,
			height,
			geometry,
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

}

module.exports = {
	controller: [ '$scope', '$ngRedux', MapComponentController ],
	controllerAs: 'vm',
	template: [
		'<svg ng-attr-width="{{vm.width}}" ng-attr-height="{{vm.height}}" class="map__svg">',
			'<g class="map__group">',
				'<path ng-repeat="g in vm.geometry track by $index" ng-attr-d="{{ g.geometry }}" ng-attr-fill="{{ g.properties.fill }}" ng-click="vm.onConstituencyClick(g.id)" class="map__constituency" ng-class="{ \'map__constituency--active\': (vm.filter.activeConstituency === g.id  || vm.filter.activeCounty === g.county_id || vm.filter.activeParty === g.party_id ) }"></path>',
			'</g>',
		'</svg>'
	].join('')
}
