import { createElement } from "@wordpress/element";
import { Icon, TabPanel } from '@wordpress/components';
import { desktop, help, mobile, tablet } from '@wordpress/icons';
import { DEFAULT, DESKTOP, MOBILE, TABLET } from '../utils';
const tabs = [{
  name: 'default',
  title: createElement(Icon, {
    icon: help
  }),
  device: DEFAULT
}, {
  name: 'desktop',
  title: createElement(Icon, {
    icon: desktop
  }),
  device: DESKTOP
}, {
  name: 'tablet',
  title: createElement(Icon, {
    icon: tablet
  }),
  device: TABLET
}, {
  name: 'mobile',
  title: createElement(Icon, {
    icon: mobile
  }),
  device: MOBILE
}];
export function ResponsiveSettingsTabs(props) {
  const {
    children
  } = props;
  return createElement(TabPanel, {
    tabs: tabs
  }, children);
}