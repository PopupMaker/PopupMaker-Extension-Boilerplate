const eslintConfig = {
	root: true,
	extends: [ 'plugin:@code-atlantic/eslint-plugin/recommended' ],
	globals: {
		wp: 'readonly',
		PUM: 'readonly',
		PUM_Admin: 'readonly',
		window: 'readonly',
	},
	env: {
		browser: true,
		jquery: true,
	},
	settings: {
		jsdoc: {
			mode: 'typescript',
		},
		'import/resolver': {
			node: {
				moduleDirectory: [ 'node_modules' ],
			},
		},
	},
	parserOptions: {
		requireConfigFile: false,
		babelOptions: {
			presets: [ require.resolve( '@wordpress/babel-preset-default' ) ],
		},
	},
	rules: {
		'no-unused-vars': 1,
	},
};

module.exports = eslintConfig;
