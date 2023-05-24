import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { filterEntries } from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';

export default function TabsSave( props ) {
	const { attributes } = props;

	const {
		accentColor,
		activeAccentColor,
		textColor,
		activeTextColor,
		contentBackgroundColor,
		contentTextColor,
		tabsAlignment,
	} = attributes;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			[ `wp-block-statik-tabs--nav-align-${ tabsAlignment }` ]:
				Boolean( tabsAlignment ),
		} ),
		style: filterEntries(
			{
				'--statik-tabs--accentColor': accentColor,
				'--statik-tabs--activeAccentColor': activeAccentColor,
				'--statik-tabs--textColor': textColor,
				'--statik-tabs--activeTextColor': activeTextColor,
				'--statik-tabs--contentBackgroundColor': contentBackgroundColor,
				'--statik-tabs--contentTextColor': contentTextColor,
			},
			( [ , v ] ) => v !== ''
		),
	} );

	const innerBlocksProps = useInnerBlocksProps.save( {
		className: 'wp-block-statik-tabs__inner-blocks',
	} );

	return (
		<div { ...blockProps }>
			<TabsNav attributes={ attributes } />
			<div { ...innerBlocksProps } />
		</div>
	);
}

const TabsNav = ( props ) => {
	const { attributes } = props;

	const { tabs = [] } = attributes;

	return (
		<nav className="wp-block-statik-tabs__nav">
			<ul className="wp-block-statik-tabs__nav-list" role="tablist">
				{ tabs.map( ( tab ) => (
					<TabsNavItem
						key={ tab.blockId }
						tab={ tab }
						attributes={ attributes }
					/>
				) ) }
			</ul>
		</nav>
	);
};

const TabsNavItem = ( props ) => {
	const { tab, attributes } = props;

	const { tabs, preSelectedTab, descriptionEnabled } = attributes;

	const blockIndex = tabs.findIndex(
		( _tab ) => _tab.blockId === tab.blockId
	);

	const isActiveTab = Number( preSelectedTab ) === blockIndex;

	const tabId = `wp-block-${ tab.blockId }-tab`;
	const tabpanelId = `wp-block-${ tab.blockId }-tabpanel`;

	return (
		<li
			key={ tab.blockId }
			className={ clsx( 'wp-block-statik-tabs__nav-list-item', {
				'wp-block-statik-tabs__nav-list-item--selected': isActiveTab,
				[ tab.tabClassName ]: Boolean( tab.tabClassName ),
			} ) }
		>
			<button
				className="wp-block-statik-tabs__nav-item"
				type="button"
				role="tab"
				aria-selected={ isActiveTab }
				aria-controls={ tabpanelId }
				tabIndex={ isActiveTab ? 0 : -1 }
				id={ tabId }
			>
				<p className="wp-block-statik-tabs__nav-item-title">
					{ tab.title }
				</p>
				{ descriptionEnabled && (
					<p className="wp-block-statik-tabs__nav-item-desc">
						{ tab.description }
					</p>
				) }
			</button>
		</li>
	);
};
