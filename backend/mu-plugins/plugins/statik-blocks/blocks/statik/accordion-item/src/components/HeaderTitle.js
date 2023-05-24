import { __ } from '@wordpress/i18n';
import { RichText } from '@wordpress/block-editor';
import { useDispatch } from '@wordpress/data';
import {
	useBlockAttributes,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';

export const HeaderTitle = () => {
	const { attributes, setAttributes } = useBlockAttributes();
	const { title, headerSelector } = attributes;

	const clientId = useBlockClientId();
	const { removeBlock } = useDispatch( 'core/block-editor' );

	const handleRemove = ( forward ) => {
		const hasEmptyTitle =
			typeof title === 'undefined' ||
			( typeof title !== 'undefined' && title.length === 0 );

		if ( ! forward && hasEmptyTitle ) {
			removeBlock( clientId );
		}
	};

	const handleChange = ( newTitle ) => {
		setAttributes( { title: newTitle } );
	};

	return (
		<RichText
			tagName={ headerSelector }
			placeholder={ __( 'Write accordion item title…', 'statik-blocks' ) }
			value={ title }
			className={ 'wp-block-statik-accordion-item__title' }
			onChange={ handleChange }
			onRemove={ handleRemove }
		/>
	);
};
