import { Icon, TabPanel } from '@wordpress/components';
import { desktop, help, mobile, tablet } from '@wordpress/icons';
import { DEFAULT, DESKTOP, MOBILE, TABLET } from '../utils';

const tabs = [
	{
		name: 'default',
		title: <Icon icon={ help } />,
		device: DEFAULT,
	},
	{
		name: 'desktop',
		title: <Icon icon={ desktop } />,
		device: DESKTOP,
	},
	{
		name: 'tablet',
		title: <Icon icon={ tablet } />,
		device: TABLET,
	},
	{
		name: 'mobile',
		title: <Icon icon={ mobile } />,
		device: MOBILE,
	},
];

export function ResponsiveSettingsTabs( props ) {
	const { children } = props;

	return <TabPanel tabs={ tabs }>{ children }</TabPanel>;
}
