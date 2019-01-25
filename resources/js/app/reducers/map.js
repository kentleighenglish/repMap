const { MAP_TYPES } = require('../actions/map');
const { reduce } = require('lodash');
const d3 = require('d3');

const INITIAL_STATE = {
	width: 1000,
	height: 1000,
	constituencies: []
}

const createFeatures = constituencies => reduce(constituencies, (arr, c) => {
	const { party: { id: party_id, colour } } = c.members[0];
	const { id: county_id } = c.county;

	const item = {
		id: c.cty16cd,
		county_id,
		party_id,
		type: 'Feature',
		geometry: JSON.parse(c.geojson),
		properties: { fill: colour ? '#262325' : null }
	}

	return [
		...arr,
		item
	];
}, []);


const createGeometryGenerator = (state, features) => {
	var projection = d3.geoAzimuthalEqualArea()
	.center([-1.9, 52.5])
	.fitSize([state.width, state.height], { type: 'FeatureCollection', features: features });

	var geoGenerator = d3.geoPath()
	.projection(projection);

	return geoGenerator;
}



const calculateNewGeometry = state => {
	var features = createFeatures(state.constituencies);
	if (features.length) {
		var geometryGenerator = createGeometryGenerator(state, features);

		state.geometry = reduce(features, (arr, f) => {
			return [
				...arr,
				{
					id: f.id,
					properties: f.properties,
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


	if (state.width && state.height && !state.geometry && state.constituencies) {
		state = calculateNewGeometry(state);
	}

	return {
		...INITIAL_STATE,
		...state
	};
}
