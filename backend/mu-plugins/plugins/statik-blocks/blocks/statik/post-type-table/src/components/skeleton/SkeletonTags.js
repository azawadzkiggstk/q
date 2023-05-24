import { Skeleton } from '@statik-space/wordpress-statik-blocks';

export function SkeletonTags() {
	return (
		<>
			<li>
				<Skeleton.Inline>Lorem</Skeleton.Inline>
			</li>
			<li>
				<Skeleton.Inline>Dolor amet</Skeleton.Inline>
			</li>
		</>
	);
}
