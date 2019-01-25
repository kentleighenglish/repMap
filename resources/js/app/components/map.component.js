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
				'<path ng-repeat="g in vm.geometry track by $index" ng-attr-d="{{ g.geometry }}" fill="{{ g.properties.fill }}" ng-click="vm.onConstituencyClick(g.id)" class="map__constituency" ng-class="{ \'map__constituency--active\': vm.filter.activeConstituency === g.id }"></path>',
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
