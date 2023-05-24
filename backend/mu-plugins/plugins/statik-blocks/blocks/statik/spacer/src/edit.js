import { InspectorControls } from './components/ui/InspectorControls';
import { useBlockProps } from '@wordpress/block-editor';
import { ResizableBox } from '@wordpress/components';
import { useSpacerState } from './hooks/useSpacerState';
import { MIN_SPACER_HEIGHT } from './consts';
import './editor.scss';

export default function SpacerEdit( props ) {
	const { isSelected } = props;

	const {
		boxHeight,
		isResizing,
		handleResizeStart,
		handleResize,
		handleResizeStop,
	} = useSpacerState();

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls />
			<div { ...blockProps }>
				<ResizableBox
					className="wp-block-statik-spacer__resizable-box"
					size={ {
						height: boxHeight,
					} }
					enable={ {
						top: false,
						right: false,
						bottom: true,
						left: false,
					} }
					minHeight={ MIN_SPACER_HEIGHT }
					onResizeStart={ handleResizeStart }
					onResize={ handleResize }
					onResizeStop={ handleResizeStop }
					showHandle={ isSelected }
					__experimentalShowTooltip={ true }
					__experimentalTooltipProps={ {
						axis: 'y',
						position: 'bottom',
						isVisible: isResizing,
					} }
				/>
			</div>
		</>
	);
}
