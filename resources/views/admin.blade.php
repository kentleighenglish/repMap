@extends('layouts.main')

@section('content')
	<div class="container" ng-controller="AppController as app" ng-cloak>
		<aside class="sidebar">
			<ul class="menu">
				<li>Elected Members</li>
				<li>Constituencies</li>
				<li>Issues</li>
			</ul>
		</aside>
		<ul>
			<li ng-repeat="m in app.members">@{{ m.fullname}}</li>
		</ul>
	</div>
@endsection

@section('scripts')
	<script src="/js/admin.js" type="text/javascript"></script>
@endsection
