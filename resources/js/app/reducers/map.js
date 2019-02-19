const { MAP_TYPES } = require('../actions/map');
const { reduce, find } = require('lodash');
const d3 = require('d3');

const INITIAL_STATE = {
	width: 1000,
	height: 1000,
	constituencies: {},
	geometry: [],
	parsedGeometry: null,
	counties: [],
	parties: [],
}

const createFeatures = (geometry, constituencies) => reduce(geometry, (arr, g) => {
	const item = {
		type: 'Feature',
		geometry: JSON.parse(g.geojson),
		properties: { fill: '#262325' }
	}

	if (g.constituency_id && find(constituencies, { id: g.constituency_id })) {
		const c = find(constituencies, { id: g.constituency_id });
		const { party: { id: party_id = null, colour = null } = {} } = c.elected_member ? c.elected_member : {};
		const { id: county_id } = c.county;

		item.id = c.cty16cd;
		item.county_id = county_id;
		item.party_id = party_id;
		item.properties =  { fill: colour || '#262325' }
	}

	return [
		...arr,
		item
	];
}, []);


const createGeometryGenerator = ({ width, height, center }, data) => {
	var projection = d3.geoAzimuthalEqualArea()
	.center(center)
	.fitSize([width, height], data);

	var geoGenerator = d3.geoPath()
	.projection(projection);

	return geoGenerator;
}

const calculateNewGeometry = state => {
	const { width, height } = state;
	var features = createFeatures(state.geometry, state.constituencies);
	if (features.length) {
		var geometryGenerator = createGeometryGenerator({ width, height, center: [-1.9, 52.5] }, { type: 'FeatureCollection', features: features });

		state.parsedGeometry = reduce(features, (arr, f) => {
			return [
				...arr,
				{
					...f,
					geometry: geometryGenerator(f)
				}
			]
		}, []);
	}

	return state;
};


module.exports = (state = INITIAL_STATE, action) => {
	switch(action.type) {
		case MAP_TYPES.RECEIVE_CONSTITUENCIES:
			state.constituencies = action.items;
		break;
		case MAP_TYPES.RECALCULATE_GEOMETRY:
			if (state.constituencies) {
				calculateNewGeometry(state);
			}
		break;
	}


	if (state.width && state.height && !state.parsedGeometry && state.geometry) {
		state = calculateNewGeometry(state);
	}

	return {
		...INITIAL_STATE,
		...state
	};
}
