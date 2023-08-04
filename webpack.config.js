const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const glob = require( 'glob' );

const entry = {};
[ 'plugin-admin', 'plugin-blocks', 'plugin-public', 'plugin-settings' ].forEach(
	( script ) =>
		( entry[ script ] = path.resolve(
			process.cwd(),
			`assets/src/${ script }.js`
		) )
);

// Scan the assets/src/blocks directory for index.js or frontend.js files and add them to the entry object.
glob.sync( 'assets/src/blocks/**/{frontend,index}.js' ).forEach( ( file ) => {
	const name = file.includes( 'frontend.js' )
		? file
				.replace( '/frontend.js', '' )
				.replace( 'assets/src/blocks/', '' ) + '-frontend'
		: file.replace( '/index.js', '' ).replace( 'assets/src/blocks/', '' );

	entry[ name ] = path.resolve( process.cwd(), file );
} );

module.exports = {
	...defaultConfig,
	entry,
	output: {
		path: path.join( __dirname, './assets/build' ),
	},
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
	},
};
