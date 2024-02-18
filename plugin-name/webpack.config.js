const path = require( 'path' );
// The following will be useful once Popup Maker core has its own dependency extraction plugin.
// const CustomTemplatedPathPlugin = require( './packages/custom-templated-path-webpack-plugin' );
// const DependencyExtractionWebpackPlugin = require( './packages/dependency-extraction-webpack-plugin' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const NODE_ENV = process.env.NODE_ENV || 'development';

const packages = {
	admin: 'src/admin',
	frontend: 'src/frontend',
};

const config = {
	...defaultConfig,
	// Maps our buildList into a new object of { key: build.entry }.
	entry: Object.entries( packages ).reduce(
		( entry, [ packageName, packagePath ] ) => {
			entry[ packageName ] = path.resolve(
				process.cwd(),
				packagePath
			);
			return entry;
		},
		{}
	),
	output: {
		filename: ( data ) => {
			const name = data.chunk.name;
			return '[name].js';
		},
		path: path.resolve( process.cwd(), 'dist' ),
		devtoolNamespace: '{PLUGIN_SLUG}',
		devtoolModuleFilenameTemplate:
			'webpack://[namespace]/[resource-path]?[loaders]'
	},
	resolve: {
		extensions: [ '.json', '.js', '.jsx', '.ts', '.tsx' ],
		alias: {
			...defaultConfig.resolve.alias,
			...Object.entries( packages ).reduce(
				( alias, [ packageName, packagePath ] ) => {
					alias[ `@{PLUGIN_SLUG}/${ packageName }` ] = path.resolve(
						__dirname,
						packagePath
					);

					return alias;
				},
				{}
			),
		},
	},
	plugins: [
		...defaultConfig.plugins,
		// The following will be useful once Popup Maker core has its own dependency extraction plugin.
		/*
		...defaultConfig.plugins.filter(
			( plugin ) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
		new CustomTemplatedPathPlugin( {
			modulename( outputPath, data ) {
				const entryName = data.chunk.name;
				if ( entryName ) {
					// Convert the dash-case name to a camel case module name.
					// For example, 'csv-export' -> 'csvExport'
					return entryName.replace( /-([a-z])/g, ( match, letter ) =>
						letter.toUpperCase()
					);
				}
				return outputPath;
			},
		} ),
		new DependencyExtractionWebpackPlugin( {
			// injectPolyfill: true,
			// useDefaults: true,
		} ),
		*/
	],
	optimization: {
		...defaultConfig.optimization,
		minimize: NODE_ENV !== 'development',
	},
};

module.exports = config;
