
const d3 = require('d3');

const state = window.__INITIAL_STATE__;

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


const { module, bootstrap } = require('angular');
const { createStore, applyMiddleware } = require('redux');
const { createLogger } = require('redux-logger');
const thunk = require('redux-thunk').default;

require('ng-redux');

const axios = require('axios');
const _ = require('lodash');

require('./app.module');

const initialState = window.__INITIAL_STATE__;

var middleware = [
	thunk
];

if (process.env.NODE_ENV === 'development') {
	middleware = [
		...middleware,
		createLogger()
	];
}

const rootReducer = require('./reducers');
const store = createStore(rootReducer, initialState, applyMiddleware(...middleware));

module('RepMap', [
	'AppModule',
	'ngRedux'
])
.factory('axios', () => axios)
.factory('_', () => _)
.factory('d3Service', () => d3)
.config(['$ngReduxProvider', ($ngReduxProvider) => {
	$ngReduxProvider.provideStore(store);
}]);

bootstrap(document, [ 'RepMap' ]);
