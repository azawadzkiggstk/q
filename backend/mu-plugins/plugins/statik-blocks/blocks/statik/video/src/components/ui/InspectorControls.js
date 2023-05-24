import {
	Button,
	ExternalLink,
	FocalPointPicker,
	PanelBody,
	PanelRow,
	RangeControl,
	SelectControl,
	TextareaControl,
	ToggleControl,
	__experimentalUnitControl as UnitControl,
	BaseControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	__experimentalColorGradientControl as ColorGradientControl,
	InspectorControls as WPInspectorControls,
	useSetting,
} from '@wordpress/block-editor';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { mediaPosition } from '../../utils/mediaPosition';

export function InspectorControls( props ) {
	const { bgImageRef } = props;

	const { attributes, setAttributes } = useBlockAttributes();
	const {
		height,
		width,
		hasOverlay,
		followUp,
		overlayColor,
		overlayColorOpacity,
		overlayImage,
		overlayHasParallax,
		overlayIsRepeated,
		overlayFocalPoint,
		overlayAltText,
	} = attributes;

	const showFocalPointPicker =
		overlayImage && ( ! overlayHasParallax || overlayIsRepeated );

	const colors = useSetting( 'color.palette' ) || [];

	const setHeight = ( value ) => {
		setAttributes( {
			height: value,
		} );
	};

	const setWidth = ( value ) => {
		setAttributes( {
			width: value,
		} );
	};

	const setHasOverlay = ( value ) => {
		setAttributes( {
			hasOverlay: value,
		} );
	};

	const toggleOverlayHasParallax = () => {
		setAttributes( {
			overlayHasParallax: ! overlayHasParallax,
			...( ! overlayHasParallax ? { overlayFocalPoint: undefined } : {} ),
		} );
	};

	const toggleOverlayIsRepeated = () => {
		setAttributes( {
			overlayIsRepeated: ! overlayIsRepeated,
		} );
	};

	const setOverlayColor = ( value ) => {
		setAttributes( {
			overlayColor: value,
		} );
	};

	const setOverlayColorOpacity = ( value ) => {
		setAttributes( {
			overlayColorOpacity: value,
		} );
	};

	const setOverlayFocalPoint = ( value ) => {
		setAttributes( {
			overlayFocalPoint: value,
		} );
	};

	const setOverlayAltText = ( value ) => {
		setAttributes( {
			overlayAltText: value,
		} );
	};

	const clearOverlayImage = () => {
		setAttributes( {
			overlayImage: undefined,
			overlayFocalPoint: undefined,
			overlayHasParallax: undefined,
			overlayIsRepeated: undefined,
		} );
	};

	const setFollowUp = ( value ) => {
		setAttributes( {
			followUp: value,
		} );
	};

	const imperativeFocalPointPreview = ( value ) => {
		const [ styleOfRef, property ] = [
			bgImageRef.current.style,
			'backgroundPosition',
		];
		styleOfRef[ property ] = mediaPosition( value );
	};

	return (
		<WPInspectorControls>
			<PanelBody title={ __( 'Dimensions', 'statik-blocks' ) }>
				<BaseControl>
					<UnitControl
						label={ __(
							'Height of a video block',
							'statik-blocks'
						) }
						value={ height }
						isPressEnterToChange={ true }
						onChange={ setHeight }
						step={ 1 }
						__unstableInputWidth="80px"
					/>
				</BaseControl>
				<BaseControl>
					<UnitControl
						label={ __(
							'Width of a video block',
							'statik-blocks'
						) }
						value={ width }
						isPressEnterToChange={ true }
						onChange={ setWidth }
						step={ 1 }
						__unstableInputWidth="80px"
					/>
				</BaseControl>
			</PanelBody>
			<PanelBody title={ __( 'Overlay Settings', 'statik-blocks' ) }>
				<ToggleControl
					label={ __( 'Display Overlay', 'statik-blocks' ) }
					checked={ hasOverlay }
					onChange={ setHasOverlay }
				/>
				{ hasOverlay && (
					<>
						<ColorGradientControl
							label={ __( 'Background color', 'statik-blocks' ) }
							colorValue={ overlayColor }
							colors={ colors }
							gradients={ undefined }
							disableCustomColors={ false }
							disableCustomGradients={ true }
							onColorChange={ setOverlayColor }
						/>
						<RangeControl
							label={ __( 'Opacity', 'statik-blocks' ) }
							value={ overlayColorOpacity }
							onChange={ setOverlayColorOpacity }
							min={ 0 }
							max={ 100 }
							step={ 10 }
							required
						/>
					</>
				) }
			</PanelBody>
			{ overlayImage && (
				<PanelBody title={ __( 'Media settings', 'statik-blocks' ) }>
					<ToggleControl
						label={ __( 'Fixed background', 'statik-blocks' ) }
						checked={ overlayHasParallax }
						onChange={ toggleOverlayHasParallax }
					/>
					<ToggleControl
						label={ __( 'Repeated background', 'statik-blocks' ) }
						checked={ overlayIsRepeated }
						onChange={ toggleOverlayIsRepeated }
					/>
					{ showFocalPointPicker && (
						<FocalPointPicker
							label={ __(
								'Focal point picker',
								'statik-blocks'
							) }
							url={ overlayImage }
							value={ overlayFocalPoint }
							onDragStart={ imperativeFocalPointPreview }
							onDrag={ imperativeFocalPointPreview }
							onChange={ setOverlayFocalPoint }
						/>
					) }
					<TextareaControl
						label={ __(
							'Alt text (alternative text)',
							'statik-blocks'
						) }
						value={ overlayAltText }
						onChange={ setOverlayAltText }
						help={
							<>
								<ExternalLink href="https://www.w3.org/WAI/tutorials/images/decision-tree">
									{ __(
										'Describe the purpose of the image',
										'statik-blocks'
									) }
								</ExternalLink>
								{ __(
									'Leave empty if the image is purely decorative.',
									'statik-blocks'
								) }
							</>
						}
					/>
					<PanelRow>
						<Button
							variant="secondary"
							isSmall
							className="block-library-cover__reset-button"
							onClick={ clearOverlayImage }
						>
							{ __( 'Clear Media', 'statik-blocks' ) }
						</Button>
					</PanelRow>
				</PanelBody>
			) }
			<PanelBody title={ __( 'Follow-up action', 'statik-blocks' ) }>
				<SelectControl
					label={ __( 'Follow-up behavior', 'statik-blocks' ) }
					options={ [
						{ value: 'none', label: __( 'None', 'statik-blocks' ) },
						{
							value: 'modal',
							label: __( 'Display in a modal', 'statik-blocks' ),
						},
						{
							value: 'redirect',
							label: __(
								'Redirect to the video',
								'statik-blocks'
							),
						},
					] }
					value={ followUp }
					onChange={ setFollowUp }
				/>
			</PanelBody>
		</WPInspectorControls>
	);
}
