import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { useDispatch } from '@wordpress/data';
import clsx from 'clsx';

export const ControlNavButton = ( props ) => {
	const { index, blockId } = props;

	const { attributes, setAttributes } = useBlockAttributes();
	const { selectBlock } = useDispatch( 'core/block-editor' );
	const { selectedSlideIndex } = attributes;

	const selectSlide = () => {
		setAttributes( { selectedSlideIndex: index } );
		selectBlock( blockId );
	};

	const className = clsx( {
		'wp-block-statik-slider__control-nav-button': true,
		'wp-block-statik-slider__control-nav-button--selected':
			selectedSlideIndex === index,
	} );

	return <button className={ className } onClick={ selectSlide } />;
};
