const { combineReducers } = require('redux');

const constituencies = require('./constituencies');
const filter = require('./filter');
// const issues = require('./issues');

module.exports = combineReducers({
	constituencies,
	filter: filter,
	issues: (state = {}) => state
});
