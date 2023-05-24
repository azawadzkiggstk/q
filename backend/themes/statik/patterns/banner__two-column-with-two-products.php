<?php
/**
 * Title: Banner with two products
 * Slug: statik/banner-two-columns-product-with-two-products
 * Categories: banner
 */
?>

<!-- wp:core/group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"0px"}}},"textColor":"black","gradient":"purple-to-yellow","layout":{"inherit":true}} -->
<div class="wp-block-group alignfull has-black-color has-purple-to-yellow-gradient-background has-text-color has-background" style="padding-top:100px;padding-bottom:0px">
    <!-- wp:core/columns -->
    <div class="wp-block-columns">
        <!-- wp:core/column -->
        <div class="wp-block-column">
            <!-- wp:core/heading {"textAlign":"center"} -->
            <h2 class="has-text-align-center"><strong>Our designs always look good!</strong></h2>
            <!-- /wp:core/heading -->

            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Sed sed eleifend nibh, nec tincidunt tortor. Suspendisse accumsan urna ac nisl porttitor interdum. Pellentesque ac eros congue, fermentum enim id, blandit felis. Fusce in quam tempus, fringilla massa venenatis, ti ncidunt mi. Maecenas at tortor id nisl interdum interdum eu in sem.</p>
            <!-- /wp:paragraph -->

            <!-- wp:statik/spacer {"height":["70px"]} /-->

            <!-- wp:core/columns -->
            <div class="wp-block-columns">
                <!-- wp:core/column -->
                <div class="wp-block-column">
                    <!-- wp:core/image {"sizeSlug":"large"} -->
                    <figure class="wp-block-image size-large"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-6.webp" alt="" /></figure>
                    <!-- /wp:core/image -->
                </div>
                <!-- /wp:core/column -->

                <!-- wp:core/column -->
                <div class="wp-block-column">
                    <!-- wp:core/image {"sizeSlug":"large"} -->
                    <figure class="wp-block-image size-large"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-5.webp" alt="" /></figure>
                    <!-- /wp:core/image -->
                </div>
                <!-- /wp:core/column -->
            </div>
            <!-- /wp:core/columns -->
        </div>
        <!-- /wp:core/column -->
    </div>
    <!-- /wp:core/columns -->
</div>
<!-- /wp:core/group -->