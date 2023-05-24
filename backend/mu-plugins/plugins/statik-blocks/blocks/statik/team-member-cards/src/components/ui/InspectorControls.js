import {
	PanelBody,
	BaseControl,
	RangeControl,
	ToggleControl,
	SelectControl,
	__experimentalDivider as Divider,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	InspectorAdvancedControls,
	InspectorControls as WPInspectorControls,
} from '@wordpress/block-editor';
import {
	QueryControl,
	useBlockAttributes,
	ResponsiveSettingsTabs,
	useRwdAttribute,
} from '@statik-space/wordpress-statik-blocks';
import { DEFAULT_PER_PAGE } from '../../consts';

export function InspectorControls() {
	const { attributes, setAttributes } = useBlockAttributes();

	const {
		query,
		cardsPerRow,
		displayImage,
		displayName,
		displayShortDescription,
		displayLongDescription,
		displaySocialMedia,
		displayReadMoreButton,
		followUpArea,
		followUpBehaviour,
		modalContent,
	} = attributes;

	const isCardClickable = followUpArea !== 'none';
	const hasModal = followUpBehaviour === 'modal';

	const rwdCardsPerRow = useRwdAttribute( cardsPerRow );

	const setRWDAttribute = ( key, rwdAttribute, device ) => ( value ) => {
		rwdAttribute.setDeviceValue( device, value );
		setAttributes( {
			[ key ]: rwdAttribute.toRwd(),
		} );
	};

	const { per_page: perPage = DEFAULT_PER_PAGE } = query;

	const handleQueryItemChange = ( key ) => ( value ) => {
		setAttributes( {
			query: {
				...query,
				[ key ]: value,
			},
		} );
	};

	const setFollowUpArea = ( value ) => {
		setAttributes( { followUpArea: value } );
	};

	const setFollowUpBehaviour = ( value ) => {
		setAttributes( { followUpBehaviour: value } );
	};

	const setModalContent = ( value ) => {
		setAttributes( { modalContent: value } );
	};

	const toggleAttribute = ( key ) => () => {
		const currentValue = attributes[ key ];
		setAttributes( { [ key ]: ! currentValue } );
	};

	const handleApplyQuery = ( value ) => {
		setAttributes( { query: value } );
	};

	return (
		<>
			<WPInspectorControls>
				<PanelBody title={ __( 'Settings', 'statik-blocks' ) }>
					<RangeControl
						label={ __(
							'Number of Cards to display',
							'statik-blocks'
						) }
						value={ parseInt( perPage ) }
						onChange={ handleQueryItemChange( 'per_page' ) }
						min={ 1 }
						max={ 12 }
						required
					/>
					<ResponsiveSettingsTabs>
						{ ( tab ) => {
							return (
								<>
									<Divider marginTop={ '0 !important' } />
									<RangeControl
										label={ __(
											'Number of Cards to display in a row'
										) }
										value={ rwdCardsPerRow.getDeviceValue(
											tab.name
										) }
										onChange={ setRWDAttribute(
											'cardsPerRow',
											rwdCardsPerRow,
											tab.name
										) }
										min={ 1 }
										max={ 4 }
									/>
								</>
							);
						} }
					</ResponsiveSettingsTabs>
				</PanelBody>
				<PanelBody title={ __( 'Layout', 'statik-blocks' ) }>
					<ToggleControl
						label={ __( 'Display Image', 'statik-blocks' ) }
						checked={ displayImage }
						onChange={ toggleAttribute( 'displayImage' ) }
					/>
					<ToggleControl
						label={ __( 'Display Name', 'statik-blocks' ) }
						checked={ displayName }
						onChange={ toggleAttribute( 'displayName' ) }
					/>
					<ToggleControl
						label={ __(
							'Display short description',
							'statik-blocks'
						) }
						checked={ displayShortDescription }
						onChange={ toggleAttribute(
							'displayShortDescription'
						) }
					/>
					<ToggleControl
						label={ __(
							'Display long description',
							'statik-blocks'
						) }
						checked={ displayLongDescription }
						onChange={ toggleAttribute( 'displayLongDescription' ) }
					/>
					<ToggleControl
						label={ __(
							'Display "Social Media" section',
							'statik-blocks'
						) }
						checked={ displaySocialMedia }
						onChange={ toggleAttribute( 'displaySocialMedia' ) }
					/>
					<ToggleControl
						label={ __(
							'Display Read More Button',
							'statik-blocks'
						) }
						checked={ displayReadMoreButton }
						onChange={ toggleAttribute( 'displayReadMoreButton' ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Follow-up action', 'statik-blocks' ) }>
					<SelectControl
						label={ __( 'Follow-up area', 'statik-blocks' ) }
						options={ [
							{
								value: 'none',
								label: __( 'No follow-up', 'statik-blocks' ),
							},
							{
								value: 'area',
								label: __(
									'Card area is clickable',
									'statik-blocks'
								),
							},
							{
								value: 'button',
								label: __(
									'Read More button is clickable',
									'statik-blocks'
								),
								disabled: ! displayReadMoreButton,
							},
						] }
						value={ followUpArea }
						onChange={ setFollowUpArea }
					/>
					{ isCardClickable && (
						<SelectControl
							label={ __(
								'Follow-up behavior',
								'statik-blocks'
							) }
							options={ [
								{
									value: 'redirect',
									label: __( 'Redirect', 'statik-blocks' ),
								},
								{
									value: 'modal',
									label: __(
										'Display in a modal',
										'statik-blocks'
									),
								},
							] }
							value={ followUpBehaviour }
							onChange={ setFollowUpBehaviour }
						/>
					) }
					{ hasModal && (
						<SelectControl
							label={ __( 'Modal content', 'statik-blocks' ) }
							options={ [
								{
									value: 'content',
									label: __( 'Content', 'statik-blocks' ),
								},
								{
									value: 'website',
									label: __(
										"From the person's URI",
										'statik-blocks'
									),
								},
							] }
							value={ modalContent }
							onChange={ setModalContent }
						/>
					) }
				</PanelBody>
			</WPInspectorControls>
			<InspectorAdvancedControls>
				<BaseControl>
					<QueryControl
						value={ query }
						onApplyQuery={ handleApplyQuery }
					/>
				</BaseControl>
			</InspectorAdvancedControls>
		</>
	);
}
