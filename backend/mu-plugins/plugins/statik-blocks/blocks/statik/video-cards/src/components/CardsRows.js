import { createElement } from '@wordpress/element';

export function CardsRows( props ) {
	const { entities, cardComponent } = props;

	return (
		<>
			{ entities.map( ( entity ) =>
				createElement( cardComponent, { key: entity.id, entity } )
			) }
		</>
	);
}
