import { createElement, Fragment } from "@wordpress/element";
import { Notice, TextControl } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import queryString from 'query-string';
import { useState } from '@wordpress/element';
export function QueryControl(props) {
  const {
    value,
    onApplyQuery
  } = props;
  const [innerQuery, setInnerQuery] = useState(undefined);
  const query = queryString.stringify(value, {
    encode: false
  });
  const handleQueryControlFocus = () => {
    setInnerQuery(query);
  };
  const handleQueryControlChange = newQueryString => {
    setInnerQuery(newQueryString);
  };
  const handleQueryControlBlur = () => {
    const values = queryString.parse(innerQuery);
    setInnerQuery(undefined);
    if (onApplyQuery) {
      onApplyQuery(values);
    }
  };
  const noticeContent = sprintf(
  // translators: %1$s: <a>, %2$s: </a>, %3$s: <code>, %4$s: string[], %5$s: </code>
  __('It is possible to alter some properties of the WordPress query for fetching cards. The allowed params of WP-API can be found %1$shere%2$s.', 'statik'), '<a target="_blank" rel="noopener noreferrer" href="https://developer.wordpress.org/rest-api/reference/posts/#arguments">', '</a>');
  return createElement(Fragment, null, createElement(TextControl, {
    label: __('Modify WordPress query manually', 'statik'),
    value: innerQuery !== null && innerQuery !== void 0 ? innerQuery : query,
    onFocus: handleQueryControlFocus,
    onChange: handleQueryControlChange,
    onBlur: handleQueryControlBlur,
    placeholder: __('search=example&orderby=date', 'statik')
  }), createElement(Notice, {
    isDismissible: false,
    status: 'info'
  }, createElement("span", {
    dangerouslySetInnerHTML: {
      __html: noticeContent
    }
  })));
}