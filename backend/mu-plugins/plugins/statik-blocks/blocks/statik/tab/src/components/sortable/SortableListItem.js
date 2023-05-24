import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

export function SortableListItem( props ) {
	const { id, className, children } = props;

	const { attributes, listeners, setNodeRef, transform, transition } =
		useSortable( { id } );

	const style = {
		transform: CSS.Translate.toString( transform ),
		transition,
	};

	return (
		<li
			className={ className }
			ref={ setNodeRef }
			style={ style }
			{ ...attributes }
			{ ...listeners }
		>
			{ children }
		</li>
	);
}
