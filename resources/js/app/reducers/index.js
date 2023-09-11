const { combineReducers } = require('redux');

const map = require('./map');
const filter = require('./filter');
// const issues = require('./issues');

module.exports = combineReducers({
	map,
	filter,
	issues: (state = {}) => state
});
