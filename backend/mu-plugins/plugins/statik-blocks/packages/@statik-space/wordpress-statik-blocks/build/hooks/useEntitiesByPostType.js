import { useSelect } from '@wordpress/data';
const defaultParams = {
  parent: 0,
  order: 'asc',
  orderby: 'id',
  per_page: -1
};
export function useEntitiesByPostType() {
  let postType = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'page';
  let params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : defaultParams;
  const {
    entities,
    isResolvingEntities,
    hasResolvedEntities
  } = useSelect(select => {
    const {
      getEntityRecords,
      isResolving,
      hasFinishedResolution
    } = select('core');
    const entitiesParameters = ['postType', postType, params];
    return {
      entities: getEntityRecords(...entitiesParameters) || null,
      isResolvingEntities: isResolving('getEntityRecords', entitiesParameters),
      hasResolvedEntities: hasFinishedResolution('getEntityRecords', entitiesParameters)
    };
  }, [postType, params]);
  return {
    entities,
    isResolvingEntities,
    hasResolvedEntities,
    hasEntities: !!(hasResolvedEntities && entities?.length)
  };
}