import { createElement, Fragment } from '@wordpress/element';
import clsx from 'clsx';
import './Skeleton.scss';

export function Skeleton( props ) {
	const { tag = 'div', className, children, ...restProps } = props;

	return createElement(
		tag,
		{
			className: clsx( 'skeleton', className ),
			...restProps,
		},
		[
			children,
			<span key="skeleton-activity" className="skeleton__activity">
				<span />
			</span>,
		]
	);
}

function SkeletonInline( props ) {
	return <Skeleton tag="span" { ...props } />;
}

function SkeletonText( props ) {
	const { tag = 'div', children, ...restProps } = props;

	const newChildren = [ children ]
		.flat()
		.filter( ( child ) => typeof child === 'string' )
		.map( ( child ) => {
			return child.split( ' ' ).map( ( word, wordIndex ) => {
				return (
					<Fragment key={ wordIndex }>
						<Skeleton.Inline { ...restProps }>
							{ word }
						</Skeleton.Inline>{ ' ' }
					</Fragment>
				);
			} );
		} );

	return createElement( tag, {}, newChildren.flat() );
}

Skeleton.Inline = SkeletonInline;
Skeleton.Text = SkeletonText;
