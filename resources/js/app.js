
window.d3 = require('d3');

const state = window.__INITIAL_STATE__;

var width = 1000;
var height = 1000;

var colours = {
	'Labour': '#bd3a2c',
	'Social Democratic & Labour Party': '#bd3a2c',
	'Labour (Co-op)': '#bd3a2c',
	'Conservative': '#5281c2',
	'Liberal Democrat': '#e9bb41',
	'Green Party': '#8bb54f',
	'Scottish National Party': '#f1ea3d',
	'UK Independence Party': '#623579',
	'Democratic Unionist Party': '#aa392e',
	'Sinn Féin': '#326760',
	'Ulster Unionist Party': '#9999FF',
	'Alliance': '#FFD700',
	'Respect': '#801e0b',
	'Independent': '#FFFFFF',
	'Speaker': '#000000',
	'Plaid Cymru': '#348837'
}

var geojson = {
	type: 'FeatureCollection',
	features: state.constituencies.reduce(function(arr, c) {
		party = c.members[0].party;

		if (c.geojson) {
			var colour = '#374549';
			if (colours[party.name]) {
				colour = colours[party.name];
			} else {
				console.log(party.name);
			}

			arr.push({
				type: 'Feature',
				geometry: JSON.parse(c.geojson),
				properties: { fill: colour, stroke: '#374549', strokeWidth: '.2' }
			});
		}

		return arr;
	}, [])
};

console.log(geojson);

var projection = d3.geoAzimuthalEqualArea()
.center([-1.9, 52.5])
.fitSize([width, height], geojson);

var geoGenerator = d3.geoPath()
.projection(projection);

d3.select('.map__group')
.selectAll('path')
.data(geojson.features)
.enter()
.append('path')
.attr('d', geoGenerator)
.style('fill', function(d) {
	return d.properties.fill
})
.style('stroke', function(d) {
	return d.properties.stroke
})
.style('stroke-width', function(d) {
	return d.properties.strokeWidth
});
