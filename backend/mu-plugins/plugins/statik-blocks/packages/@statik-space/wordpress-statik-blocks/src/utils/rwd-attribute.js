export const DEFAULT = 0;
export const DESKTOP = 1;
export const TABLET = 2;
export const MOBILE = 3;

const devicesNames = [ 'default', 'desktop', 'tablet', 'mobile' ];

const getDeviceIndex = ( deviceName ) => {
	return devicesNames.indexOf( deviceName );
};

const getDeviceName = ( deviceIndex ) => {
	return devicesNames[ deviceIndex ];
};

export const rwdAttribute = ( attribute ) => {
	return new RwdAttribute( attribute );
};

class RwdAttribute {
	attribute = [];

	constructor( attribute ) {
		this.attribute = [ ...attribute ];
	}

	get default() {
		return this.attribute[ DEFAULT ];
	}
	setDefault( value ) {
		this.attribute[ DEFAULT ] = value;
	}

	get desktop() {
		return this.attribute[ DESKTOP ];
	}
	setDesktop( value ) {
		this.attribute[ DESKTOP ] = value;
	}

	get tablet() {
		return this.attribute[ TABLET ];
	}
	setTablet( value ) {
		this.attribute[ TABLET ] = value;
	}

	get mobile() {
		return this.attribute[ MOBILE ];
	}
	setMobile( value ) {
		this.attribute[ MOBILE ] = value;
	}

	getDeviceValue( deviceName ) {
		const deviceIndex = getDeviceIndex( deviceName );
		return this.attribute[ deviceIndex ];
	}

	setDeviceValue( deviceName, value ) {
		const deviceIndex = getDeviceIndex( deviceName );
		this.attribute[ deviceIndex ] = value;
	}

	get inherit() {
		return {
			default: getAttribute( this.attribute, DEFAULT ),
			desktop: getAttribute( this.attribute, DESKTOP ),
			tablet: getAttribute( this.attribute, TABLET ),
			mobile: getAttribute( this.attribute, MOBILE ),
		};
	}

	map( callback ) {
		return [
			{
				breakpoint: null,
				value: getAttribute( this.attribute, DEFAULT ),
			},
			{
				breakpoint: 1000,
				value: getAttribute( this.attribute, DESKTOP ),
			},
			{ breakpoint: 500, value: getAttribute( this.attribute, TABLET ) },
			{ breakpoint: 0, value: getAttribute( this.attribute, MOBILE ) },
		].map( callback );
	}

	assignObject( ...source ) {
		this.attribute = assignObject( this.attribute, ...source );
		return this.attribute;
	}

	assignArray( ...source ) {
		this.attribute = assignArray( this.attribute, ...source );
		return this.attribute;
	}

	toRwd() {
		return this.attribute.map( ( value ) => value ?? '' );
	}

	toObject() {
		return toObject( this.attribute );
	}
}

const getAttribute = ( attribute, device = DEFAULT ) => {
	if ( ! Array.isArray( attribute ) ) {
		throw new Error(
			`Attribute value has to be array, but ${ typeof attribute } given.`
		);
	}

	switch ( device ) {
		case MOBILE:
		case TABLET:
		case DESKTOP:
		case DEFAULT: {
			return getInheritedValue( attribute, device );
		}
		default: {
			return getInheritedValue( attribute, DEFAULT );
		}
	}
};

const getInheritedValue = ( value, index ) => {
	if (
		( value[ index ] !== undefined &&
			value[ index ] !== null &&
			value[ index ] !== '' ) ||
		index === 0
	) {
		return value[ index ];
	}
	return getInheritedValue( value, Math.max( index - 1, 0 ) );
};

const toObject = ( rwdArray ) => {
	return rwdArray.reduce( ( rwdObject, deviceValue, deviceIndex ) => {
		const deviceName = getDeviceName( deviceIndex );
		rwdObject[ deviceName ] = deviceValue;
		return rwdObject;
	}, {} );
};

const toArray = ( rwdObject ) => {
	return devicesNames.reduce( ( rwdArray, deviceName, deviceIndex ) => {
		rwdArray[ deviceIndex ] = rwdObject[ deviceName ];
		return rwdArray;
	}, [] );
};

const assignObject = ( rwdArray, ...source ) => {
	const rwdObject = toObject( rwdArray );
	const finalRwdObject = Object.assign( {}, rwdObject, ...source );
	return toArray( finalRwdObject );
};

const assignArray = ( rwdArray, ...source ) => {
	return Object.entries( Object.assign( {}, rwdArray, ...source ) )
		.sort( ( a, b ) => parseInt( a[ 0 ] ) - parseInt( b[ 0 ] ) )
		.map( ( value ) => value[ 1 ] );
};
