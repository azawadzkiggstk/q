module.exports = {
	root: true,
	extends: [
		'plugin:@wordpress/eslint-plugin/recommended',
		'plugin:eslint-comments/recommended',
	],
	globals: {
		wp: 'off',
		statikBlocks: 'readonly',
		ResizeObserver: 'readonly',
	},
	rules: {
		'@wordpress/no-unsafe-wp-apis': 'off',
		'no-console': 'off',
		'eslint-comments/no-unused-disable': 2,
	},
};
