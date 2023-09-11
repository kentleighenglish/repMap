const { module, bootstrap } = require('angular');
const { createStore, applyMiddleware } = require('redux');
const { createLogger } = require('redux-logger');
const thunk = require('redux-thunk').default;

require('ng-redux');

// External Libraries
const axios = require('axios');
const _ = require('lodash');
const d3 = require('d3');

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
