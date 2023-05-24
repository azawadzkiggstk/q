import clsx from 'clsx';
import { BaseControl } from '@wordpress/components';

export const ImagePicker = ( props ) => {
	const { label, value, options, onChange } = props;

	return (
		<BaseControl
			label={ label }
			className="maps-image-picker"
			id="map-image-picker"
		>
			{ options.map( ( option ) => {
				return (
					<button
						key={ `image-picker-${ option.value }` }
						onClick={ () => onChange( option.value, option ) }
						aria-pressed={ ( value === option.value ).toString() }
						className={ clsx( 'maps-image-picker-item', {
							'maps-image-picker-item-active':
								value === option.value,
						} ) }
					>
						{ option.image && 'string' === typeof option.image && (
							<img
								src={ option.image }
								alt={ option.label || option.value }
							/>
						) }
						{ option.image &&
							'string' !== typeof option.image &&
							option.image }
						{ option.label && <span>{ option.label }</span> }
					</button>
				);
			} ) }
		</BaseControl>
	);
};
