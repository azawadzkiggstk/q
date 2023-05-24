import { useEffect } from '@wordpress/element';
import { getBlockType } from '@wordpress/blocks';
import { useBlockEditContext } from '@wordpress/block-editor';
import { applyFilters } from '@wordpress/hooks';
import { useBlockAttributes } from './useBlockAttributes';
import { clearEmpties } from '../utils';
import { isEmpty } from 'lodash';
const BLOCK_PROPS_KEY = 'blockProps';
const BLOCK_ATTS_KEY = 'blockAtts';
export function useSetBlockProps() {
  const {
    attributes,
    setAttributes
  } = useBlockAttributes();
  const {
    name
  } = useBlockEditContext();
  const attributesValues = Object.entries(attributes).filter(_ref => {
    let [attName] = _ref;
    return attName !== BLOCK_PROPS_KEY && attName !== BLOCK_ATTS_KEY;
  }).sort((_ref2, _ref3) => {
    let [attNameA] = _ref2;
    let [attNameB] = _ref3;
    return attNameA.localeCompare(attNameB);
  }).map(_ref4 => {
    let [, attValue] = _ref4;
    return attValue;
  });
  useEffect(() => {
    const blockProps = applyFilters('blocks.getSaveContent.extraProps', {}, getBlockType(name), attributes);
    clearEmpties(blockProps);
    if (!isEmpty(blockProps)) {
      setAttributes({
        blockProps
      });
    } else {
      setAttributes({
        blockProps: undefined
      });
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [JSON.stringify(attributesValues)]);
}