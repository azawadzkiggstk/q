const path = require( 'path' );
const { assign } = require( 'lodash' );
const camelCase = require( 'camelcase' );
const wpScriptsWebpackConfig = require( '@wordpress/scripts/config/webpack.config' );
const globby = require( 'globby' );
const { merge } = require( 'webpack-merge' );
const mergeCustomAssetResourceRule = require( './utils/merge-custom-asset-resource-rule' );
const mergeCustomPlugins = require( './utils/merge-custom-plugins' );

const createEntrypoints = () => {
	return globby
		.sync( './blocks/*/*/src/(index|view).js' )
		.reduce( ( acc, entryPath ) => {
			const regexpResult = entryPath.match(
				/\/([^\/]+)\/([^\/]+)\/src\/(index|view)\.js$/
			);
			const [ , namespace, blockName, fileName ] = regexpResult;
			const blockEntry = `${ namespace }/${ blockName }/build/${ fileName }`;

			acc[ blockEntry ] = {
				import: entryPath,
			};

			if ( fileName === 'view' ) {
				const blockJsonPath = path.resolve(
					'./blocks',
					namespace,
					blockName,
					'block.json'
				);
				const blockJson = require( blockJsonPath );
				const libraryName = camelCase(
					blockJson.name.replace( '/', '-' ).concat( 'Block' ),
					{ pascalCase: true }
				);
				assign( acc[ blockEntry ], {
					library: {
						name: libraryName,
						type: 'var',
						export: 'default',
					},
				} );
			}

			return acc;
		}, {} );
};

let config = merge( wpScriptsWebpackConfig, {
	name: 'blocks',
	entry: createEntrypoints(),
	output: {
		filename: './[name].js',
		path: path.resolve( process.cwd(), 'blocks' ),
	},
} );

config = mergeCustomAssetResourceRule( config );
config = mergeCustomPlugins( config );
module.exports = config;
