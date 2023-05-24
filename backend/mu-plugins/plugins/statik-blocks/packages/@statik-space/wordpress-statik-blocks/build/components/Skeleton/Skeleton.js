import _extends from "@babel/runtime/helpers/extends";
import { createElement, Fragment } from '@wordpress/element';
import clsx from 'clsx';
import './Skeleton.scss';
export function Skeleton(props) {
  const {
    tag = 'div',
    className,
    children,
    ...restProps
  } = props;
  return createElement(tag, {
    className: clsx('skeleton', className),
    ...restProps
  }, [children, createElement("span", {
    key: "skeleton-activity",
    className: "skeleton__activity"
  }, createElement("span", null))]);
}
function SkeletonInline(props) {
  return createElement(Skeleton, _extends({
    tag: "span"
  }, props));
}
function SkeletonText(props) {
  const {
    tag = 'div',
    children,
    ...restProps
  } = props;
  const newChildren = [children].flat().filter(child => typeof child === 'string').map(child => {
    return child.split(' ').map((word, wordIndex) => {
      return createElement(Fragment, {
        key: wordIndex
      }, createElement(Skeleton.Inline, restProps, word), ' ');
    });
  });
  return createElement(tag, {}, newChildren.flat());
}
Skeleton.Inline = SkeletonInline;
Skeleton.Text = SkeletonText;