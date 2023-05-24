import { DirectionNavButton } from './DirectionNavButton';
import { ArrowRight } from './icons/ArrowRight';
import { ArrowLeft } from './icons/ArrowLeft';

export const DirectionNav = () => {
	return (
		<div className="wp-block-statik-slider__direction-nav">
			<DirectionNavButton offset={ -1 }>
				<ArrowLeft />
			</DirectionNavButton>
			<DirectionNavButton offset={ 1 }>
				<ArrowRight />
			</DirectionNavButton>
		</div>
	);
};
