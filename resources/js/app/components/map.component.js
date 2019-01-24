const { setActiveConstituency } = require('app/actions/filter');
const { reduce } = require('lodash');

class MapComponentController {

	constructor($ngRedux, d3Service) {
		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);
		this.d3 = d3Service;

		this.width = 1000;
		this.height = 1000;

	}

	mapStateToThis({ constituencies, filter }) {
		return {
			items: reduce(constituencies, (arr, c) => {
				const { party: { colour } } = c.members[0];

				const item = {
					id: c.id,
					type: 'Feature',
					geometry: JSON.parse(c.geojson),
					properties: { fill: colour ? '#262325' : null }
				}

				if (filter.activeConstituency === c.id) {
					item.active = true;
				}

				return [
					...arr,
					item
				];
			}, []),
			filter
		}
	}

	mapDispatchToThis(dispatch) {
		return {
			setActive: (id) => dispatch(setActiveConstituency(id))
		}
	}

	$onInit() {
		this.geometryGenerator = this.createGeometryGenerator();
	}

	createGeometryGenerator() {
		var projection = this.d3.geoAzimuthalEqualArea()
		.center([-1.9, 52.5])
		.fitSize([this.width, this.height], { type: 'FeatureCollection', features: this.items });

		var geoGenerator = this.d3.geoPath()
		.projection(projection);

		return geoGenerator;
	}

	onConstituencyClick(c) {
		this.setActive(c.id);
	}

}

module.exports = {
	controller: [ '$ngRedux', 'd3Service', MapComponentController ],
	controllerAs: 'vm',
	template: [
		'<svg ng-attr-width="{{vm.width}}" ng-attr-height="{{vm.height}}" class="map__svg">',
			'<g class="map__group">',
				'<path ng-repeat="g in vm.items track by g.id" ng-attr-d="{{ vm.geometryGenerator(g) }}" fill="{{ g.properties.fill }}" ng-click="vm.onConstituencyClick(g)" class="map__constituency" ng-class="{ \'map__constituency--active\': !!g.active }"></path>',
			'</g>',
		'</svg>'
	].join('')
}

// var width = 1000;
// var height = 1000;
//
// var geojson = {
// 	type: 'FeatureCollection',
// 	features: state.constituencies.reduce(function(arr, c) {
// 		var party = c.members[0].party;
//
// 		if (c.geojson) {
// 			var colour = '#191F21';
// 			// var colour = '#374549';
// 			if (colours[party.name]) {
// 				// colour = colours[party.name];
// 			}
//
// 			arr.push({
// 				type: 'Feature',
// 				geometry: JSON.parse(c.geojson),
// 				properties: { fill: colour, stroke: '#374549', strokeWidth: '.2' }
// 			});
// 		}
//
// 		return arr;
// 	}, [])
// };
//
// var projection = d3.geoAzimuthalEqualArea()
// .center([-1.9, 52.5])
// .fitSize([width, height], geojson);
//
// var geoGenerator = d3.geoPath()
// .projection(projection);
//
// d3.select('.map__group')
// .selectAll('path')
// .data(geojson.features)
// .enter()
// .append('path')
// .attr('d', geoGenerator)
// .style('fill', function(d) {
// 	return d.properties.fill
// })
// .style('stroke', function(d) {
// 	return d.properties.stroke
// })
// .style('stroke-width', function(d) {
// 	return d.properties.strokeWidth
// });
