const { CONSTITUENCIES_TYPES } = require('../actions/constituencies');

const INITIAL_STATE = []

module.exports = (state = INITIAL_STATE, action) => {
	switch(action.type) {
		case CONSTITUENCIES_TYPES.RECEIVE_CONSTITUENCIES:
			state = action.items;
		break;
	}
	return state;
}
