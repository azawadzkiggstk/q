import clsx from 'clsx';
import { Slides } from './components/Slides';
import { DirectionNav } from './components/DirectionNav';
import { ControlNav } from './components/ControlNav';
import { useBlockProps } from '@wordpress/block-editor';
import { BlockControls } from './components/ui/BlockControls';
import { InspectorControls } from './components/ui/InspectorControls';
import { useSliderState } from './hooks/useSliderState';
import { useSliderEffect } from './hooks/useSliderEffect';
import './editor.scss';

export default function SliderEdit() {
	const { showDirectionNav, showControlNav, autoHeight } = useSliderState();

	useSliderEffect();

	const blockProps = useBlockProps( {
		className: clsx( {
			'wp-block-statik-slider--auto-height': Boolean( autoHeight ),
		} ),
	} );

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<Slides />
				{ showDirectionNav && <DirectionNav /> }
				{ showControlNav && <ControlNav /> }
			</div>
		</>
	);
}
