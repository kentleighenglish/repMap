<?php

return [
	// last general election resource ID found at http://eldaddp.azurewebsites.net/elections
	"lastGeneralElectionId" => "730039",
	// URL to fetch geojson for United Kingdom (dated 2016)
	"geoJsonUrl" => "http://geoportal1-ons.opendata.arcgis.com/datasets/48d0b49ff7ec4ad0a4f7f8188f6143e8_3.geojson",
	"issueMethods" => [
		"electoralResults" => [
			"label" => "Electoral Results API",
			"properties" => [
				// If ever the amount of elected MPs change from 650, the pageSize will need to change here
				"url" => "http://eldaddp.azurewebsites.net/electionresults.json?_pageSize=650&electionId="
			]
		]
	],
	"partyAliases" => [
		"Labour" => [ "Lab" ],
		"Conservative" => [ "Con" ],
		"UK Independence Party" => [ "UKIP", "United Kingdom Independence Party" ],
		"Scottish National Party" => [ "SNP" ],
		"Liberal Democrat" => [ "LD" ],
		"Plaid Cymru" => [ "PC" ],
		"Democratic Unionist Party" => [ "DUP" ],
		"Sinn Féin" => [ "SF" ],
		"Green Party" => [ "Green" ],
		"Independent" => [ "Ind" ],
		"Speaker" => [ "Spk" ]
	],
	'partyColours' => [
		'Labour' => '#bd3a2c',
		'Social Democratic & Labour Party' => '#bd3a2c',
		'Labour (Co-op)' => '#bd3a2c',
		'Conservative' => '#5281c2',
		'Liberal Democrat' => '#e9bb41',
		'Green Party' => '#8bb54f',
		'Scottish National Party' => '#f1ea3d',
		'UK Independence Party' => '#623579',
		'Democratic Unionist Party' => '#aa392e',
		'Sinn Féin' => '#326760',
		'Ulster Unionist Party' => '#9999FF',
		'Alliance' => '#FFD700',
		'Respect' => '#801e0b',
		'Independent' => '#FFFFFF',
		'Speaker' => '#000000',
		'Plaid Cymru' => '#348837'
	]
];
