<!DOCTYPE html>
<html>
	<head>
		<title>Representation Map</title>

		<link href="https://fonts.googleapis.com/css?family=Catamaran:400,700|Raleway:300,400" rel="stylesheet">
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
