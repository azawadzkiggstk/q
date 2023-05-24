const path = require( 'path' );
const wpScriptsWebpackConfig = require( '@wordpress/scripts/config/webpack.config' );

const getAllowedPlugins = ( plugins ) => {
	const disallowedPlugins = [ 'CleanWebpackPlugin' ];
	return plugins.filter(
		( plugin ) =>
			disallowedPlugins.includes( plugin.constructor.name ) === false
	);
};
const plugins = getAllowedPlugins( wpScriptsWebpackConfig.plugins );

module.exports = {
	...wpScriptsWebpackConfig,
	mode: 'production',
	name: 'editor',
	entry: {
		index: {
			import: './editor/index.js',
			filename: 'index.min.js',
		},
	},
	output: {
		path: path.resolve( process.cwd(), './editor/build' ),
	},
	plugins: [ ...plugins ],
};
