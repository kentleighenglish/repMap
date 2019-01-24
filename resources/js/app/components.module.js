const { module } = require('angular');

const MapComponent = require('./components/map.component');

module('ComponentsModule', [])
.component('map', MapComponent);
