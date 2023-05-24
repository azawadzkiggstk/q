import { RichText } from '@wordpress/block-editor';
import {
	useBlockAttributes,
	useBlockIndex,
} from '@statik-space/wordpress-statik-blocks';
import { useDispatch } from '@wordpress/data';
import clsx from 'clsx';
import { __ } from '@wordpress/i18n';
import { SortableListItem } from './sortable/SortableListItem';

export function TabsNavItem( props ) {
	const { tabsRootId, tabBlockId } = props;

	const { attributes, setAttributes } = useBlockAttributes( tabsRootId );
	const { activeTab, descriptionEnabled } = attributes;

	const { attributes: tabAttributes, setAttributes: setTabAttributes } =
		useBlockAttributes( tabBlockId );
	const { title, description, tabClassName } = tabAttributes;

	const blockIndex = useBlockIndex( tabBlockId );

	const { selectBlock } = useDispatch( 'core/block-editor' );

	const selectTab = async () => {
		setAttributes( {
			activeTab: blockIndex,
		} );
		await selectBlock( tabBlockId );
	};

	const isActiveTab = activeTab === blockIndex;

	const className = clsx( {
		'wp-block-statik-tabs__nav-item': true,
	} );

	const handleTitleChange = ( newTitle ) => {
		setTabAttributes( {
			title: newTitle,
		} );
	};

	const handleDescriptionChange = ( newDescription ) => {
		setTabAttributes( {
			description: newDescription,
		} );
	};

	return (
		<SortableListItem
			id={ tabBlockId }
			className={ clsx( {
				'wp-block-statik-tabs__nav-list-item': true,
				'wp-block-statik-tabs__nav-list-item--selected': isActiveTab,
				[ tabClassName ]: Boolean( tabClassName ),
			} ) }
		>
			<button className={ className } onClick={ selectTab }>
				{ ! isActiveTab && (
					<>
						<RichText.Content
							className="wp-block-statik-tabs__nav-item-title"
							tagName="p"
							value={
								title || __( 'Tab name…', 'statik-blocks' )
							}
						/>
						{ descriptionEnabled && (
							<RichText.Content
								className="wp-block-statik-tabs__nav-item-desc"
								tagName="p"
								value={
									description ||
									__( 'Tab description…', 'statik-blocks' )
								}
							/>
						) }
					</>
				) }
				{ isActiveTab && (
					<>
						<RichText
							className="wp-block-statik-tabs__nav-item-title"
							tagName="p"
							value={ title }
							onChange={ handleTitleChange }
							placeholder={ __( 'Tab name…', 'statik-blocks' ) }
							withoutInteractiveFormatting
							allowedFormats={ [] }
						/>
						{ descriptionEnabled && (
							<RichText
								className="wp-block-statik-tabs__nav-item-desc"
								tagName="p"
								value={ description }
								onChange={ handleDescriptionChange }
								placeholder={ __(
									'Tab description…',
									'statik-blocks'
								) }
								withoutInteractiveFormatting
								allowedFormats={ [] }
							/>
						) }
					</>
				) }
			</button>
		</SortableListItem>
	);
}
