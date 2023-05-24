import { InspectorControls } from './components/ui/InspectorControls';
import { useBlockProps } from '@wordpress/block-editor';
import { ResizableBox } from '@wordpress/components';
import { useUniversalBlockState } from './hooks/useUniversalBlockState';
import { MIN_SPACER_HEIGHT } from './consts';
import './editor.scss';

export default function UniversalBlockEdit( props ) {
	const { isSelected } = props;

	const {
		boxHeight,
		isResizing,
		handleResizeStart,
		handleResize,
		handleResizeStop,
	} = useUniversalBlockState();

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls />
			<div { ...blockProps }>
				<ResizableBox
					className="wp-block-statik-universal-block__resizable-box"
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
				>
					Universal Block
				</ResizableBox>
			</div>
		</>
	);
}
