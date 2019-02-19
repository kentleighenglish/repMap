<!DOCTYPE html>
<html>
	<head>
		<title>Representation Map</title>
		<link href="/css/app.css" rel="stylesheet" />
	</head>
	<body>
		@yield('content')
	</body>
	<script>
		window.__INITIAL_STATE__ = @json($viewState)
	</script>
	@yield('scripts')
</html>
