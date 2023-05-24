import { Button, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { FixedSizeGrid } from 'react-window';
import { useEffect, useRef, useState } from '@wordpress/element';
import {
	useBlockAttributes,
	useDebounce,
} from '@statik-space/wordpress-statik-blocks';
import IconoirIcons from '../../icons/iconoir-icons';
import { Iconoir } from '../Iconoir';

const icons = Object.keys( IconoirIcons );

const ICON_SIZE = 24;
const CELL_SIZE = 50;

export const IconsCollection = () => {
	const ref = useRef( null );
	const gridRef = useRef( null );
	const { setAttributes } = useBlockAttributes();
	const [ iconName, setIconName ] = useState( '' );
	const [ iconsList, setIconsList ] = useState( icons );
	const debouncedIconName = useDebounce( iconName, 500 );

	const [ boxWidth, setBoxWidth ] = useState( 100 );

	useEffect( () => {
		const refInstance = ref.current;

		const resizeObserver = new ResizeObserver( function ( entries ) {
			const rect = entries[ 0 ].contentRect;
			setBoxWidth( rect.width );
		} );

		resizeObserver.observe( refInstance );

		return () => {
			resizeObserver.unobserve( refInstance );
		};
	}, [] );

	useEffect( () => {
		if ( debouncedIconName ) {
			setIconsList(
				icons.filter( ( icon ) =>
					icon.toLowerCase().includes( debouncedIconName )
				)
			);
		} else {
			setIconsList( icons );
		}
	}, [ debouncedIconName ] );

	const columnCount = Math.floor( boxWidth / CELL_SIZE );
	const rowCount = iconsList.length / columnCount;
	const boxHeight = 140;

	const getCellRender = ( { columnIndex, rowIndex, style } ) => {
		const index = columnIndex + rowIndex * columnCount;
		return (
			<div
				key={ index }
				className={ 'wp-block-statik-icon__icon-cell' }
				style={ {
					...style,
				} }
			>
				<Button
					icon={ <Iconoir icon={ iconsList[ index ] } /> }
					size={ ICON_SIZE }
					onClick={ () => {
						setAttributes( {
							icon: iconsList[ index ],
						} );
					} }
				/>
			</div>
		);
	};

	return (
		<div style={ { width: '100%' } } ref={ ref }>
			<TextControl
				value={ iconName }
				onChange={ ( value ) => setIconName( value ) }
				placeholder={ __( 'Filter icons…', 'statik-blocks' ) }
			/>
			<div
				style={ {
					position: 'relative',
					height: boxHeight,
					width: '100%',
				} }
			>
				<div
					style={ {
						position: 'absolute',
						height: boxHeight,
						width: boxWidth,
					} }
				>
					<FixedSizeGrid
						innerRef={ gridRef }
						columnCount={ columnCount }
						columnWidth={ CELL_SIZE }
						height={ boxHeight }
						rowCount={ rowCount }
						rowHeight={ CELL_SIZE }
						width={ boxWidth }
					>
						{ getCellRender }
					</FixedSizeGrid>
				</div>
			</div>
		</div>
	);
};
