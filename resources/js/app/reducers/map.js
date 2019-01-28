const { MAP_TYPES } = require('../actions/map');
const { reduce, flatten } = require('lodash');
const d3 = require('d3');

const INITIAL_STATE = {
	width: 1000,
	height: 1000,
	constituencies: {},
	geometry: null,
	counties: [],
	parties: [],
	extra: [],
	extraGeometry: null
}

const createFeatures = constituencies => reduce(constituencies, (arr, c) => {
	const { party: { id: party_id, colour } } = c.elected_member;
	const { id: county_id } = c.county;

	const item = {
		id: c.cty16cd,
		county_id,
		party_id,
		type: 'Feature',
		geometry: JSON.parse(c.geojson),
		properties: { fill: colour || '#262325' }
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
	var features = createFeatures(state.constituencies);
	if (features.length) {
		var geometryGenerator = createGeometryGenerator({ width, height, center: [-1.9, 52.5] }, { type: 'FeatureCollection', features: features });

		state.geometry = reduce(features, (arr, f) => {
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

const calculateExtraGeometry = state => {
	const { width, height } = state;
	state.extraGeometry = flatten(reduce(state.extra, (geo, collection) => {
		var features = collection.features;

		if (features.length ) {
			var geometryGenerator = createGeometryGenerator({ width: width, height: height, center: [-7.4, 53.5] }, collection);

			var geometry = reduce(features, (arr, f) => {
				return [
					...arr,
					{
						...f,
						geometry: geometryGenerator(f)
					}
				]
			}, []);

			if (geometry.length) {
				geo.push(geometry);
			}
		}

		return geo;
	}, []));

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

	if (state.width && state.height && state.extra && state.extra.length && !state.extraGeometry) {
		// state = calculateExtraGeometry(state);
	}

	return {
		...INITIAL_STATE,
		...state
	};
}
