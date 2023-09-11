const { module } = require('angular');

require('./controllers.module');
require('./components.module');
// require('./directives.module');
// require('./services.module');
// require('./filters.module');

module('AppModule', [
	'ControllersModule',
	'ComponentsModule'
]);
