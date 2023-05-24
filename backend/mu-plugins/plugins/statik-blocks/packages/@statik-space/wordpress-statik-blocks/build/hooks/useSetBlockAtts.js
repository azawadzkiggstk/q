import { useEffect } from '@wordpress/element';
import { getBlockType } from '@wordpress/blocks';
import { useBlockEditContext } from '@wordpress/block-editor';
import { isEmpty } from 'lodash';
import { useBlockAttributes } from './useBlockAttributes';
import { hasBlockIdAttributeDefinition } from '../utils';
export function useSetBlockAtts() {
  const {
    attributes,
    setAttributes
  } = useBlockAttributes();
  const {
    name
  } = useBlockEditContext();
  const blockType = getBlockType(name);
  const attributesDefs = blockType.attributes;
  const sourceAttributesEntries = Object.entries(attributes).filter(_ref => {
    let [attName] = _ref;
    return attributesDefs[attName]?.source !== undefined;
  }).filter(_ref2 => {
    let [, attValue] = _ref2;
    return attValue !== undefined;
  }).sort((_ref3, _ref4) => {
    let [attNameA] = _ref3;
    let [attNameB] = _ref4;
    return attNameA.localeCompare(attNameB);
  });
  const sourceAttributesValues = sourceAttributesEntries.map(_ref5 => {
    let [, attValue] = _ref5;
    return attValue;
  });
  useEffect(() => {
    if (!hasBlockIdAttributeDefinition(name)) {
      return;
    }
    const blockAtts = Object.fromEntries(sourceAttributesEntries);
    if (!isEmpty(blockAtts)) {
      setAttributes({
        blockAtts
      });
    } else {
      setAttributes({
        blockAtts: undefined
      });
    }

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [JSON.stringify(sourceAttributesValues)]);
}