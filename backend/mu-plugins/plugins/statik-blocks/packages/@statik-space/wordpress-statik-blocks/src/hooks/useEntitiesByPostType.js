import { useSelect } from '@wordpress/data';

const defaultParams = {
	parent: 0,
	order: 'asc',
	orderby: 'id',
	per_page: -1,
};

export function useEntitiesByPostType(
	postType = 'page',
	params = defaultParams
) {
	const { entities, isResolvingEntities, hasResolvedEntities } = useSelect(
		( select ) => {
			const { getEntityRecords, isResolving, hasFinishedResolution } =
				select( 'core' );

			const entitiesParameters = [ 'postType', postType, params ];

			return {
				entities: getEntityRecords( ...entitiesParameters ) || null,
				isResolvingEntities: isResolving(
					'getEntityRecords',
					entitiesParameters
				),
				hasResolvedEntities: hasFinishedResolution(
					'getEntityRecords',
					entitiesParameters
				),
			};
		},
		[ postType, params ]
	);

	return {
		entities,
		isResolvingEntities,
		hasResolvedEntities,
		hasEntities: !! ( hasResolvedEntities && entities?.length ),
	};
}
