import { useState, createRef } from '@wordpress/element';
import { ToolbarGroup, Button, Dropdown } from '@wordpress/components';
import { DOWN } from '@wordpress/keycodes';
import { __experimentalLinkControl as LinkControl } from '@wordpress/block-editor';

export function ReplaceUrlControl( props ) {
	const { mediaURL, onSelectURL, name = 'Replace Url' } = props;
	const [ mediaURLValue, setMediaURLValue ] = useState( mediaURL );

	const editMediaButtonRef = createRef();

	const selectURL = ( newURL ) => {
		onSelectURL( newURL );
	};

	const openOnArrowDown = ( event ) => {
		if ( event.keyCode === DOWN ) {
			event.preventDefault();
			event.stopPropagation();
			event.target.click();
		}
	};

	return (
		<Dropdown
			contentClassName="block-editor-media-replace-flow__options"
			renderToggle={ ( { isOpen, onToggle } ) => (
				<ToolbarGroup className="media-replace-flow">
					<Button
						ref={ editMediaButtonRef }
						aria-expanded={ isOpen }
						onClick={ onToggle }
						onKeyDown={ openOnArrowDown }
					>
						{ name }
					</Button>
				</ToolbarGroup>
			) }
			renderContent={ () => (
				// eslint-disable-next-line jsx-a11y/no-noninteractive-element-interactions
				<form
					className="block-editor-media-flow__url-input"
					onKeyDown={ ( event ) => {
						event.stopPropagation();
					} }
					onKeyPress={ ( event ) => {
						event.stopPropagation();
					} }
				>
					<span className="block-editor-media-replace-flow__image-url-label">
						{ 'Current media URL:' }
					</span>
					<LinkControl
						value={ { url: mediaURLValue } }
						settings={ [] }
						showSuggestions={ false }
						onChange={ ( { url } ) => {
							setMediaURLValue( url );
							selectURL( url );
							editMediaButtonRef.current.focus();
						} }
					/>
				</form>
			) }
		/>
	);
}
