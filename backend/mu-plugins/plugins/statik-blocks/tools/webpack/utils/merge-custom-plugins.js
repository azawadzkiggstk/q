const wpScriptsWebpackConfig = require( '@wordpress/scripts/config/webpack.config' );
const { mergeWithRules } = require( 'webpack-merge' );
const { DefinePlugin } = require( 'webpack' );

const getAllowedPlugins = ( plugins ) => {
	const disallowedPlugins = [ 'CleanWebpackPlugin' ];
	return plugins.filter(
		( plugin ) =>
			disallowedPlugins.includes( plugin.constructor.name ) === false
	);
};

const plugins = getAllowedPlugins( wpScriptsWebpackConfig.plugins );

const customConfig = {
	plugins: [
		...plugins,
		new DefinePlugin( {
			// Inject the `IS_GUTENBERG_PLUGIN` global, used for feature flagging.
			'process.env.IS_GUTENBERG_PLUGIN':
				process.env.npm_package_config_IS_GUTENBERG_PLUGIN,
		} ),
	],
};

const mergeCustomPlugins = ( config ) => {
	return mergeWithRules( {
		plugins: 'replace',
	} )( config, customConfig );
};

module.exports = mergeCustomPlugins;
