const { setActiveConstituency } = require('app/actions/filter');
const { resizeMap } = require('app/actions/map');
const d3 = require('d3');
const { find, filter, reduce } = require('lodash');

class MapComponentController {

	constructor($scope, $ngRedux) {
		this.$scope = $scope;

		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);

	}

	mapStateToThis({ map: { width, height, parsedGeometry: geometry, extraGeometry, geometryGenerator }, filter }) {
		return {
			width,
			height,
			geometry,
			extraGeometry,
			filter,
			projection: geometryGenerator
		}
	}

	mapDispatchToThis(dispatch) {
		return {
			setActive: (id) => dispatch(setActiveConstituency(id)),
			resizeMap: size => dispatch(resizeMap(size))
		}
	}

	$onInit() {
		// window.addEventListener('resize', () => {
		// 	this.resizeMap({ width: window.innerWidth, height: window.innerHeight });
		// });
		// this.resizeMap({ width: window.innerWidth, height: window.innerHeight });

		this.mapProps = {
			x: d3.scaleLinear().domain([0, this.width]).range([0, this.width]),
			y: d3.scaleLinear().domain([0, this.height]).range([this.height, 0]),
			scaleExtent: [ 1, 8 ]
		}

		this.zoom = d3.zoom().scaleExtent( this.mapProps.scaleExtent ).on('zoom', () => this.redraw());

		// d3.select('#mapSvg').selectAll('path')
		// .attr("transform", (d) => ("translate("+d+")"));

		d3.select('#mapSvg')
		.call( this.zoom )

		this.$scope.$watch(() => this.filter, () => {
			this.zoomTo();
		}, true);
	}

	redraw() {
		d3.select('#mapSvg').select('.map__group').attr("transform", d3.event.transform);
	}

	onConstituencyClick(key) {
		this.setActive(key);
	}

	classObject(g) {
		return {
			'map__constituency--active': this.filter.activeConstituency === g.id  || this.filter.activeCounty === g.county_id || this.filter.activeParty === g.party_id,
			'map__constituency--foreign': !g.id || !g.party_id
		}
	}

	zoomTo() {
		if (this.filter.activeConstituency) {
			this.zoomToConstituency();
		} else if(this.filter.activeParty) {
			this.zoomToParty();
		} else if(this.filter.activeCounty) {
			this.zoomToCounty();
		} else {
			this.resetZoom();
		}
	}

	zoomToConstituency() {
		const id = this.filter.activeConstituency;
		const { bounds = null } = find(this.geometry, { id });

		this.zoomToBounds(bounds);
	}

	zoomToCounty() {
		const county_id = this.filter.activeCounty;
		const paths = filter(this.geometry, { county_id });

		if (paths.length)  {
			const bounds = reduce(paths, (arr, path) => ([ ...arr, path.bounds ]));

			const groupBounds = [
				[
					d3.min(bounds, d => d[0][0]),
					d3.min(bounds, d => d[0][1])
				],
				[
					d3.max(bounds, d => d[1][0]),
					d3.max(bounds, d => d[1][1])
				]
			];

			this.zoomToBounds(groupBounds);
		} else {
			this.resetZoom();
		}
	}

	zoomToParty() {
		const party_id = this.filter.activeParty;
		const paths = filter(this.geometry, { party_id });

		if (paths.length)  {
			const bounds = reduce(paths, (arr, path) => ([ ...arr, path.bounds ]));

			const groupBounds = [
				[
					d3.min(bounds, d => d[0][0]),
					d3.min(bounds, d => d[0][1])
				],
				[
					d3.max(bounds, d => d[1][0]),
					d3.max(bounds, d => d[1][1])
				]
			];

			this.zoomToBounds(groupBounds);
		} else {
			this.resetZoom();
		}
	}


	zoomToBounds(bounds) {
		if (bounds) {
			const { width, height } = this;

			var dx = bounds[1][0] - bounds[0][0],
			dy = bounds[1][1] - bounds[0][1],
			x = (bounds[0][0] + bounds [1][0]) / 2,
			y = (bounds[0][1] + bounds[1][1]) / 2,
			scale = Math.max(1, Math.min(8, 0.9 / Math.max(dx / width, dy / height))),
			translate = [ width / 2 - scale * x, height / 2 - scale * y];

			if (!isNaN(translate[0]) && !isNaN(translate[1])) {
				d3.select('#mapSvg')
				.transition().duration(750).call( this.zoom.transform, d3.zoomIdentity.translate(translate[0], translate[1]).scale(scale) );
			}
		}
	}

	resetZoom() {
		d3.select('#mapSvg')
		.transition().duration(750).call( this.zoom.transform, d3.zoomIdentity );
	}

}

module.exports = {
	controller: [ '$scope', '$ngRedux', MapComponentController ],
	controllerAs: 'vm',
	template: [
		'<svg ng-attr-width="{{vm.width}}" ng-attr-height="{{vm.height}}" class="map__svg" id="mapSvg">',
			'<g class="map__group">',
				'<path id="{{ g.id }}" ng-repeat="g in vm.geometry track by $index" ng-attr-d="{{ g.geometry }}" ng-style="{ color: g.properties.fill }" ng-click="g.id && vm.onConstituencyClick(g.id)" class="map__constituency" ng-class="vm.classObject(g)"></path>',
				'<path ng-repeat="g in vm.extraGeometry track by $index" ng-attr-d="{{ g.geometry }}" class="map__nonconstituency"></path>',
			'</g>',
		'</svg>'
	].join('')
}
