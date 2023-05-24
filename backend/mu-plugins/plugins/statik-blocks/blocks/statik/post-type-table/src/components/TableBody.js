import { createElement } from '@wordpress/element';

export function TableBody( props ) {
	const { entities, tableRowComponent } = props;

	return (
		<tbody>
			{ entities.map( ( entity ) =>
				createElement( tableRowComponent, { key: entity.id, entity } )
			) }
		</tbody>
	);
}
