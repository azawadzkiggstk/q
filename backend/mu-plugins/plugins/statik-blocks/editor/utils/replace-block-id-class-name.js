import TokenList from '@wordpress/token-list';

export function replaceBlockIdClassName(
	className,
	currentBlockId,
	newBlockId
) {
	const CLASS_PREFIX = 'wp-block-';
	const list = new TokenList( className );

	if ( currentBlockId ) {
		list.remove( `${ CLASS_PREFIX }${ currentBlockId }` );
	}

	list.add( `${ CLASS_PREFIX }${ newBlockId }` );

	return list.value;
}
