<?php

return [
	'geoJsonUrl' => 'http://geoportal1-ons.opendata.arcgis.com/datasets/deeb99fdf09949bc8ed4dc95c80da279_4.geojson',
	"issueMethods" => [
		"electoralResults" => [
			"label" => "Electoral Results API",
			"properties" => [
				// If ever the amount of elected MPs change from 650, the pageSize will need to change here
				"url" => "http://eldaddp.azurewebsites.net/electionresults.json?_pageSize=650&electionId="
			]
		]
	]
];
