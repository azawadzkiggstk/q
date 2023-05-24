import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';
import { ResizableBox } from '@wordpress/components';
import { InspectorControls } from './components/ui/InspectorControls';
import { BlockControls } from './components/ui/BlockControls';
import { Placeholder } from './components/ui/Placeholder';
import { useVideoState } from './hooks/useVideoState';
import './editor.scss';

export default function VideoEdit( props ) {
	const { attributes, isSelected } = props;

	const { width, height, hasOverlay, overlayImage } = attributes;

	const {
		boxRef,
		bgImageRef,
		boxWidth,
		boxHeight,
		videoUrl,
		handleResizeStart,
		handleResizeStop,
		blockStyle,
		iframeStyle,
		bgStyle,
		bgImageStyle,
	} = useVideoState();

	const blockProps = useBlockProps( {
		style: blockStyle,
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-video__inner-container',
		},
		{
			template: [
				[
					'core/paragraph',
					{
						placeholder: 'Add content…',
						align: 'center',
						fontSize: 'large',
					},
				],
			],
		}
	);

	if ( ! videoUrl ) {
		return <Placeholder />;
	}

	return (
		<>
			<BlockControls />
			<InspectorControls bgImageRef={ bgImageRef } />
			<div { ...blockProps }>
				<ResizableBox
					ref={ boxRef }
					className="wp-block-statik-video__box"
					size={ {
						width: boxWidth,
						height: boxHeight,
					} }
					// minWidth={ 320 }
					minHeight={ 240 }
					enable={ {
						top: false,
						right: true,
						bottom: true,
						left: false,
					} }
					lockAspectRatio={ false }
					onResizeStart={ handleResizeStart }
					onResizeStop={ handleResizeStop }
					showHandle={ isSelected }
				>
					<iframe
						title="Video"
						width={ width }
						height={ height }
						style={ iframeStyle }
						src={ videoUrl }
						frameBorder="0"
						allow="autoplay; encrypted-media"
						allowFullScreen
					/>
					{ hasOverlay && (
						<div className="wp-block-statik-video__overlay">
							{ overlayImage && (
								<div
									className="wp-block-statik-video__background-image"
									ref={ bgImageRef }
									style={ bgImageStyle }
								/>
							) }
							<div
								className="wp-block-statik-video__background"
								style={ bgStyle }
							/>
							<div className="wp-block-statik-video__body">
								<div { ...innerBlocksProps } />
							</div>
						</div>
					) }
				</ResizableBox>
			</div>
		</>
	);
}
