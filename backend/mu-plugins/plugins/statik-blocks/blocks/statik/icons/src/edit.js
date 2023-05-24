import clsx from 'clsx';
import {
	BlockControls,
	useBlockProps,
	useInnerBlocksProps,
	JustifyContentControl,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import metadata from '../../icon/block.json';
import './editor.scss';

const { name: buttonBlockName } = metadata;

const ALLOWED_BLOCKS = [ buttonBlockName ];
const LAYOUT = {
	type: 'default',
	alignments: [],
};
const VERTICAL_JUSTIFY_CONTROLS = [ 'left', 'center', 'right' ];
const HORIZONTAL_JUSTIFY_CONTROLS = [
	'left',
	'center',
	'right',
	'space-between',
];

export default function IconsEdit( {
	attributes: { contentJustification, orientation },
	setAttributes,
} ) {
	const blockProps = useBlockProps( {
		className: clsx( {
			[ `is-content-justification-${ contentJustification }` ]:
				contentJustification,
			'is-vertical': orientation === 'vertical',
		} ),
	} );

	const preferredStyle = useSelect( ( select ) => {
		const preferredStyleVariations =
			select( blockEditorStore ).getSettings()
				.__experimentalPreferredStyleVariations;
		return preferredStyleVariations?.value?.[ buttonBlockName ];
	}, [] );

	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		template: [
			[
				buttonBlockName,
				{ className: preferredStyle && `is-style-${ preferredStyle }` },
			],
		],
		orientation,
		__experimentalLayout: LAYOUT,
		templateInsertUpdatesSelection: true,
	} );

	const justifyControls =
		orientation === 'vertical'
			? VERTICAL_JUSTIFY_CONTROLS
			: HORIZONTAL_JUSTIFY_CONTROLS;

	return (
		<>
			<BlockControls group="block" __experimentalShareWithChildBlocks>
				<JustifyContentControl
					allowedControls={ justifyControls }
					value={ contentJustification }
					onChange={ ( value ) =>
						setAttributes( { contentJustification: value } )
					}
					popoverProps={ {
						position: 'bottom right',
						isAlternate: true,
					} }
				/>
			</BlockControls>
			<div { ...innerBlocksProps } />
		</>
	);
}
