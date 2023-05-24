const { mergeWithRules } = require( 'webpack-merge' );
const path = require( 'path' );

const customConfig = {
	module: {
		rules: [
			{
				test: /\.(bmp|png|jpe?g|gif)$/i,
				type: 'asset/resource',
				generator: {
					filename: ( pathData ) => {
						const blockEntry = pathData.runtime;
						return path.join(
							blockEntry,
							'..',
							'images/[name].[hash:8][ext]'
						);
					},
				},
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/i,
				type: 'asset/resource',
				generator: {
					filename: ( pathData ) => {
						const blockEntry = pathData.runtime;
						return path.join(
							blockEntry,
							'..',
							'fonts/[name].[hash:8][ext]'
						);
					},
				},
			},
			{
				test: /\.svg$/,
				issuer: /\.(j|t)sx?$/,
				use: [
					{
						loader: '@svgr/webpack',
						options: {
							svgoConfig: {
								plugins: [
									{
										name: 'preset-default',
										params: {
											overrides: {
												removeViewBox: false,
											},
										},
									},
								],
							},
						},
					},
				],
				type: 'javascript/auto',
			},
		],
	},
};

const mergeCustomAssetResourceRule = ( config ) => {
	return mergeWithRules( {
		module: {
			rules: {
				test: 'match',
				type: 'match',
				generator: 'merge',
				use: 'replace',
			},
		},
	} )( config, customConfig );
};

module.exports = mergeCustomAssetResourceRule;
