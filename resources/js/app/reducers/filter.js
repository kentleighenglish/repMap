const { FILTER_TYPES } = require('../actions/filter');

const INITIAL_STATE = {
	activeConstituency: null
}

module.exports = (state = INITIAL_STATE, action) => {
	switch(action.type) {
		case FILTER_TYPES.SET_ACTIVE_CONSTITUENCY:
			state.activeConstituency = action.key ? action.key : null;
		break;
	}

	return state;
}
