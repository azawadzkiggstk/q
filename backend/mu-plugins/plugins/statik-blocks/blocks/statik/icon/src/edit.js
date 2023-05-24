import clsx from 'clsx';
import { InspectorControls } from './components/ui/InspectorControls';
import { BlockControls } from './components/ui/BlockControls';
import { ResizableBox } from '@wordpress/components';
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder } from './components/ui/Placeholder';
import { IconPreview } from './components/IconPreview';
import { useIconState } from './hooks/useIconState';
import './editor.scss';

export const MIN_ICON_SIZE = 32;
export const MAX_ICON_SIZE = 400;

export const MIN_ICON_SPACING = 0;
export const MAX_ICON_SPACING = 100;

export const MIN_ICON_BORDER = 0;
export const MAX_ICON_BORDER = 100;

const minHeight = MIN_ICON_SIZE + MIN_ICON_SPACING * 2 + MIN_ICON_BORDER * 2;
const maxHeight = MAX_ICON_SIZE + MAX_ICON_SPACING * 2 + MAX_ICON_BORDER * 2;

export default function IconEdit( props ) {
	const { attributes, isSelected } = props;

	const { align } = attributes;

	const { hasIcon, handleResizeStart, handleResizeStop, style, blockHeight } =
		useIconState();

	const blockProps = useBlockProps( {
		className: clsx( {
			'wp-block-statik-icon__placeholder': ! hasIcon,
			'has-block-align-left': align === 'left',
			'has-block-align-center': align === 'center',
			'has-block-align-right': align === 'right',
		} ),
	} );

	if ( ! hasIcon ) {
		return (
			<div { ...blockProps }>
				<Placeholder />
			</div>
		);
	}

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<ResizableBox
					className="wp-block-statik-icon__resizable-box"
					size={ {
						height: blockHeight,
					} }
					minHeight={ minHeight }
					maxHeight={ maxHeight }
					enable={ {
						top: false,
						right: false,
						bottom: true,
						left: false,
					} }
					lockAspectRatio={ false }
					onResizeStart={ handleResizeStart }
					onResizeStop={ handleResizeStop }
					showHandle={ isSelected }
				>
					<div className="wp-block-statik-icon__icon" style={ style }>
						<IconPreview />
					</div>
				</ResizableBox>
			</div>
		</>
	);
}
