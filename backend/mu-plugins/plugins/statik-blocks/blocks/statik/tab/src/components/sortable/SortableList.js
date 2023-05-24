import { DndContext, useSensor, useSensors, MouseSensor } from '@dnd-kit/core';
import {
	SortableContext,
	horizontalListSortingStrategy,
} from '@dnd-kit/sortable';
import { restrictToParentElement } from '@dnd-kit/modifiers';

const mouseSensorOptions = {
	activationConstraint: {
		delay: 150,
		tolerance: 5,
	},
};

export function SortableList( props ) {
	const { items, children, className, ...restProps } = props;

	const mouseSensor = useSensor( MouseSensor, mouseSensorOptions );
	const sensors = useSensors( mouseSensor );

	return (
		<ul className={ className }>
			<DndContext
				{ ...restProps }
				sensors={ sensors }
				modifiers={ [ restrictToParentElement ] }
			>
				<SortableContext
					items={ items }
					strategy={ horizontalListSortingStrategy }
				>
					{ children }
				</SortableContext>
			</DndContext>
		</ul>
	);
}
