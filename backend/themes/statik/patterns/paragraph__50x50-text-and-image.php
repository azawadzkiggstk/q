<?php
/**
 * Title: 50% - 50% text and image
 * Slug: statik/50-50-text-columns-and-image
 * Categories: paragraph
 */
?>

<!-- wp:core/columns -->
<div class="wp-block-columns">
    <!-- wp:core/column {"width":"50%"} -->
    <div class="wp-block-column" style="flex-basis:50%">
        <!-- wp:paragraph {"style":{"typography":{"fontSize":"24px"}}} -->
        <p style="font-size:24px">
            <meta charset="utf-8"><strong>Nullam vel nulla commodo</strong>
        </p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>
            <meta charset="utf-8">Nullam vel nulla commodo, dignissim magna vitae, placerat nulla. Aliquam lobortis purus eu erat tristiqueLorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a,
        </p>
        <!-- /wp:paragraph -->

        <!-- wp:core/buttons -->
        <div class="wp-block-buttons">
            <!-- wp:core/button {"className":"is-style-outline"} -->
            <div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Read more</a></div>
            <!-- /wp:core/button -->
        </div>
        <!-- /wp:core/buttons -->
    </div>
    <!-- /wp:core/column -->

    <!-- wp:core/column {"width":"50%"} -->
    <div class="wp-block-column" style="flex-basis:50%">
        <!-- wp:core/image {"sizeSlug":"large"} -->
        <figure class="wp-block-image size-large"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-11.webp" alt="" /></figure>
        <!-- /wp:core/image -->
    </div>
    <!-- /wp:core/column -->
</div>
<!-- /wp:core/columns -->