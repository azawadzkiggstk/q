import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { BlocksManagementPanel } from './components/BlocksManagementPanel';
import { statik } from '@statik-space/wordpress-statik-blocks';

function StatikSettings() {
	return (
		<>
			<PluginSidebarMoreMenuItem target="statik-settings">
				Statik Settings (experimental)
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="statik-settings"
				title="Statik Settings (experimental)"
			>
				<BlocksManagementPanel />
			</PluginSidebar>
		</>
	);
}

export const statikSettingsPlugin = {
	render: StatikSettings,
	icon: statik,
};
