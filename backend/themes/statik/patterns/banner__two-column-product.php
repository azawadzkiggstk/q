<?php
/**
 * Title: Banner with gradient
 * Slug: statik/banner-two-columns-product
 * Categories: banner
 */
?>

<!-- wp:core/group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"50px"}},"color":{"gradient":"linear-gradient(135deg,rgb(250,217,97) 0%,rgb(247,107,28) 100%)"}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull has-background" style="background:linear-gradient(135deg,rgb(250,217,97) 0%,rgb(247,107,28) 100%);padding-top:100px;padding-bottom:50px">
    <!-- wp:core/columns -->
    <div class="wp-block-columns">
        <!-- wp:core/column -->
        <div class="wp-block-column">
            <!-- wp:core/heading -->
            <h2><strong>Breathing life into brands through stunning design</strong></h2>
            <!-- /wp:core/heading -->

            <!-- wp:paragraph -->
            <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</p>
            <!-- /wp:paragraph -->

            <!-- wp:core/buttons -->
            <div class="wp-block-buttons">
                <!-- wp:core/button -->
                <div class="wp-block-button"><a class="wp-block-button__link">See more</a></div>
                <!-- /wp:core/button -->

                <!-- wp:core/button {"className":"is-style-outline"} -->
                <div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Contact Us</a></div>
                <!-- /wp:core/button -->
            </div>
            <!-- /wp:core/buttons -->
        </div>
        <!-- /wp:core/column -->

        <!-- wp:core/column -->
        <div class="wp-block-column">
            <!-- wp:core/image {"sizeSlug":"large"} -->
            <figure class="wp-block-image size-large"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-1.webp" alt="" /></figure>
            <!-- /wp:core/image -->
        </div>
        <!-- /wp:core/column -->
    </div>
    <!-- /wp:core/columns -->
</div>
<!-- /wp:core/group -->