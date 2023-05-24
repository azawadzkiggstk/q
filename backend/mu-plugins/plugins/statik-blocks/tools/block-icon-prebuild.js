#!/usr/bin/env node

const path = require( 'path' );
const globby = require( 'globby' );
const fs = require( 'fs-extra' );
const iconoirPackageJson = require( 'iconoir/package.json' );
const iconoirPackageJsonPath = require.resolve( 'iconoir/package.json' );
const iconoirModule = path.dirname( iconoirPackageJsonPath );
const iconoirIconsPath = path.join( iconoirModule, 'icons' );

const res = globby.sync( '*.svg', { cwd: iconoirIconsPath } );

const result = res.map( ( icon ) => {
	const key = path.parse( icon ).name;
	const value = `require('iconoir/icons/${ icon }').default`;
	return `\t["${ key }"]: ${ value },\n`;
} );

const fileContent = `
// @ts-nocheck
/* eslint-disable */
/* tslint:disable */

// iconoir@v${ iconoirPackageJson.version }
export default {
${ result.join( '' ) }
}
`.trimStart();

const file = path.join(
	__dirname,
	'..',
	'blocks',
	'statik',
	'icon',
	'src',
	'icons',
	'iconoir-icons.js'
);
fs.outputFile( file, fileContent, ( err ) => {
	if ( err ) {
		console.log( '[ ERROR ]\n' );
		console.log( err );
		process.exit( 1 );
	}

	console.log(
		`[ SUCCESS ]\nFile '${ path.relative(
			path.join( __dirname, '..' ),
			file
		) }' has been successfully generated.`
	);
} );
