@extends('layouts.main')

@section('content')
	<div class="container" ng-controller="AppController as app">
		<section class="sidebarContainer">
			<aside class="sidebar">
				<div>
					<button ng-click="app.resetFilter()" class="resetButton">Reset</button>
				</div>
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
			<aside class="stats">

			</aside>
		</section>
		<section class="mapContainer">
			<map class="map"></map>
		</section>
	</div>
@endsection

@section('scripts')
	<script src="/js/app.js" type="text/javascript"></script>
@endsection
