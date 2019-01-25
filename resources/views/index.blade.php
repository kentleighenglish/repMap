<!DOCTYPE html>
<html>
	<head>
		<title>Representation Map</title>
		<link href="/css/app.css" rel="stylesheet" />
	</head>
	<body>
		<div class="container" ng-controller="AppController as app">
			<aside class="sidebar">
				<div>
					<label>Constituency</label>
					<select ng-model="app.filter.activeConstituency" ng-change="app.onConstituencyChange()">
						<option disabled selected ng-value="null">Select One</option>
						<option ng-repeat="(key, c) in app.constituencies" ng-value="key">@{{ c.name }}</option>
					</select>
				</div>
				<div>
					<label>County</label>
					<select ng-model="app.filter.activeCounty" ng-change="app.onCountyChange()">
						<option disabled selected ng-value="null">Select One</option>
						<option ng-repeat="(key, c) in app.counties" ng-value="c.id">@{{ c.name }}</option>
					</select>
				</div>
				<div>
					<label>Party</label>
					<select ng-model="app.filter.activeParty" ng-change="app.onPartyChange()">
						<option disabled selected ng-value="null">Select One</option>
						<option ng-repeat="(key, c) in app.parties" ng-value="c.id">@{{ c.name }}</option>
					</select>
				</div>
			</aside>
			<section class="mapContainer">
				<map class="map"></map>
			</section>
		</div>
	</body>
	<script>
		window.__INITIAL_STATE__ = @json($viewState)
	</script>
	<script src="/js/app.js" type="text/javascript"></script>
</html>
