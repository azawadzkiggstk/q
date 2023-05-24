const getColorClassName = (type, name) => {
  if (!name || name.startsWith('#')) return '';
  switch (type) {
    case 'text-color':
      return `has-${name}-color`;
    case 'background-color':
      return `has-${name}-background-color`;
    case 'border-color':
      return `has-${name}-border-color`;
    case 'fill-color':
      return `has-${name}-fill-color`;
    default:
      return '';
  }
};
const getStyleKeyName = type => {
  switch (type) {
    case 'text-color':
      return 'color';
    case 'background-color':
      return 'backgroundColor';
    case 'border-color':
      return 'borderColor';
    case 'fill-color':
      return 'fill';
    default:
      return '';
  }
};
const getClassNameObject = (name, type, attributes) => {
  const {
    style
  } = attributes;
  const classNameObj = {};
  const classname = getColorClassName(type, attributes[name]);
  classNameObj[classname] = !!classname;
  if (attributes[name] || style?.color?.[name]) {
    switch (type) {
      case 'text-color':
        classNameObj['has-text-color'] = true;
        break;
      case 'background-color':
        classNameObj['has-background'] = true;
        break;
      case 'border-color':
        classNameObj['has-border-color'] = true;
        break;
      case 'fill-color':
        classNameObj['has-fill-color'] = true;
        break;
      default:
        break;
    }
  }
  return classNameObj;
};
const getStyleObject = (name, type, attributes) => {
  const {
    style
  } = attributes;
  const styleObj = {};
  const styleName = getStyleKeyName(type);
  styleObj[styleName] = style?.color?.[name] || undefined;
  return styleObj;
};
export const defaultColorNames = [{
  name: 'textColor',
  type: 'text-color'
}, {
  name: 'backgroundColor',
  type: 'background-color'
}];
export default function getColorAndStyleProps(attributes, classnames) {
  let colorNames = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : defaultColorNames;
  const classNameObject = colorNames.reduce((prev, curr) => {
    return {
      ...prev,
      ...getClassNameObject(curr.name, curr.type, attributes)
    };
  }, {});
  const className = classnames(classNameObject);
  const styleProp = colorNames.reduce((prev, curr) => {
    return {
      ...prev,
      ...getStyleObject(curr.name, curr.type, attributes)
    };
  }, {});
  return {
    className: !!className ? className : undefined,
    style: styleProp
  };
}