const { module } = require('angular');

const AppController = require('./controllers/app.controller');

module('ControllersModule', [])
.controller('AppController', AppController);
