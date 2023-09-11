const { FILTER_TYPES } = require('../actions/filter');

const INITIAL_STATE = {
	activeConstituency: null,
	activeCounty: null,
	activeParty: null
}

module.exports = (state = INITIAL_STATE, action) => {
	switch(action.type) {
		case FILTER_TYPES.SET_ACTIVE_CONSTITUENCY:
			state = {
				...state,
				activeConstituency: action.key ? action.key : null,
				activeCounty: null,
				activeParty: null
			}
		break;
		case FILTER_TYPES.SET_ACTIVE_COUNTY:
			state = {
				...state,
				activeCounty: action.key ? action.key : null,
				activeConstituency: null,
				activeParty: null
			}
		break;
		case FILTER_TYPES.SET_ACTIVE_PARTY:
			state = {
				...state,
				activeParty: action.key ? action.key : null,
				activeConstituency: null,
				activeCounty: null
			}
		break;
	}

	return state;
}
