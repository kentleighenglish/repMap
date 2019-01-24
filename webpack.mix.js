const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-eslint');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

mix.webpackConfig({
    resolve: {
        alias: {
			'app': path.resolve(__dirname, 'resources', 'js', 'app')
        }
    },
	module: {
		rules: [
			{
				test: /\.js?$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				query: {
					presets:[ 'env' ]
				}
			}
		]
	}
});

mix
.eslint()
.js('resources/js/app.js', 'public/js').version()
.sass('resources/sass/app.scss', 'public/css').version()
.options({
	postCss: [
		require('postcss-discard-comments')({
			removeAll: true
		})
	]
})
.browserSync({
	proxy: 'local.repmap.com',
	open: 'ui'
});
