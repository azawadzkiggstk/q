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
	useBlockAcfSchemaOptions,
} from '@statik-space/wordpress-statik-blocks';
import { DEFAULT_PER_PAGE, VIDEO_POST_TYPE } from '../../consts';

export function InspectorControls() {
	const { attributes, setAttributes } = useBlockAttributes();

	const {
		query,
		cardsPerRow,
		displayFeaturedImage,
		displayExcerpt,
		displayTitle,
		displayCategories,
		displayTags,
		displayLastUpdatedDate,
		displayReadMoreButton,
		displayPlayButton,
		followUpArea,
		destination,
		followUpBehaviour,
	} = attributes;

	const isCardClickable = followUpArea !== 'none';

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

	const setDestination = ( value ) => {
		setAttributes( { destination: value } );
	};

	const setFollowUpBehaviour = ( value ) => {
		setAttributes( { followUpBehaviour: value } );
	};

	const toggleAttribute = ( key ) => () => {
		const currentValue = attributes[ key ];
		setAttributes( { [ key ]: ! currentValue } );
	};

	const handleApplyQuery = ( value ) => {
		setAttributes( { query: value } );
	};

	const acfOptions = useBlockAcfSchemaOptions( VIDEO_POST_TYPE );

	return (
		<>
			<WPInspectorControls>
				<PanelBody title={ __( 'Settings', 'statik-blocks' ) }>
					<RangeControl
						label={ __( 'Number of Cards to display' ) }
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
											'Number of Cards to display in a row',
											'statik-blocks'
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
						label={ __(
							'Display Featured Images',
							'statik-blocks'
						) }
						checked={ displayFeaturedImage }
						onChange={ toggleAttribute( 'displayFeaturedImage' ) }
					/>
					<ToggleControl
						label={ __( 'Display Entity Title', 'statik-blocks' ) }
						checked={ displayTitle }
						onChange={ toggleAttribute( 'displayTitle' ) }
					/>
					<ToggleControl
						label={ __(
							'Display Entity Excerpt',
							'statik-blocks'
						) }
						checked={ displayExcerpt }
						onChange={ toggleAttribute( 'displayExcerpt' ) }
					/>
					<ToggleControl
						label={ __( 'Display Categories', 'statik-blocks' ) }
						checked={ displayCategories }
						onChange={ toggleAttribute( 'displayCategories' ) }
					/>
					<ToggleControl
						label={ __( 'Display Tags', 'statik-blocks' ) }
						checked={ displayTags }
						onChange={ toggleAttribute( 'displayTags' ) }
					/>
					<ToggleControl
						label={ __(
							'Display Last Updated Date',
							'statik-blocks'
						) }
						checked={ displayLastUpdatedDate }
						onChange={ toggleAttribute( 'displayLastUpdatedDate' ) }
					/>
					<ToggleControl
						label={ __(
							'Display Read More Button',
							'statik-blocks'
						) }
						checked={ displayReadMoreButton }
						onChange={ toggleAttribute( 'displayReadMoreButton' ) }
					/>
					<ToggleControl
						label={ __( 'Display "Play" Icon', 'statik-blocks' ) }
						checked={ displayPlayButton }
						onChange={ toggleAttribute( 'displayPlayButton' ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Follow-up action', 'statik-blocks' ) }>
					<SelectControl
						label={ __( 'Follow-up area', 'statik-blocks' ) }
						options={ [
							{
								value: 'none',
								label: __( 'None', 'statik-blocks' ),
							},
							{
								value: 'area',
								label: __(
									'Post area is clickable',
									'statik-blocks'
								),
							},
							{
								value: 'button',
								label: __(
									'Only Read More button is clickable',
									'statik-blocks'
								),
							},
						] }
						value={ followUpArea }
						onChange={ setFollowUpArea }
					/>
					{ isCardClickable && (
						<SelectControl
							label={ __( 'Destination', 'statik-blocks' ) }
							options={ [
								{
									value: 'default',
									label: __( 'Default', 'statik-blocks' ),
								},
								...acfOptions,
								{
									value: 'none',
									label: __(
										'No acf fields of url type associated with selected post type',
										'statik-blocks'
									),
									disabled: true,
								},
							] }
							value={ destination }
							onChange={ setDestination }
						/>
					) }
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
