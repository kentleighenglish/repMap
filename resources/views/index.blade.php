<!DOCTYPE html>
<html>
	<head>
		<title>Representation Map</title>
		<link href="/css/app.css" rel="stylesheet" />
	</head>
	<body>
		<div class="container" ng-controller="AppController as app">
			<aside>
				<label>Constituency</label>
				<select ng-model="app.filter.activeConstituency" ng-change="app.onConstituencyChange()">
					<option disabled selected ng-value="null">Select One</option>
					<option ng-repeat="c in app.constituencies" ng-value="c.id">@{{ c.name }}</option>
				</select>
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
