import { defaultMapStyle } from './default';
import { darkMapStyle } from './dark';
import { lightMapStyle } from './light';
import { nightMapStyle } from './night';

export const mapStyles = {
	[ defaultMapStyle.value ]: defaultMapStyle,
	[ lightMapStyle.value ]: lightMapStyle,
	[ darkMapStyle.value ]: darkMapStyle,
	[ nightMapStyle.value ]: nightMapStyle,
};
