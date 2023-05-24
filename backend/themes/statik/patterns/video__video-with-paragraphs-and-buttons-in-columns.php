<?php
/**
 * Title: Video and two sections with heading and paragraph
 * Slug: statik/video-with-paragraphs-and-button-in-columns
 * Categories: video
 */
?>

<!-- wp:core/columns -->
<div class="wp-block-columns">
    <!-- wp:core/column -->
    <div class="wp-block-column">
        <!-- wp:core/heading -->
        <h2>About our company</h2>
        <!-- /wp:core/heading -->

        <!-- wp:paragraph -->
        <p>Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat</p>
        <!-- /wp:paragraph -->

        <!-- wp:core/buttons -->
        <div class="wp-block-buttons">
            <!-- wp:core/button {"className":"is-style-outline"} -->
            <div class="wp-block-button is-style-outline"><a class="wp-block-button__link">About Us</a></div>
            <!-- /wp:core/button -->

            <!-- wp:core/button -->
            <div class="wp-block-button"><a class="wp-block-button__link">Meet Our Team</a></div>
            <!-- /wp:core/button -->
        </div>
        <!-- /wp:core/buttons -->
    </div>
    <!-- /wp:core/column -->

    <!-- wp:core/column {"style":{"spacing":{"padding":{"top":"30px"}}}} -->
    <div class="wp-block-column" style="padding-top:30px">
        <!-- wp:statik/video {"url":"https://youtu.be/2sr9MGkkeks"} /-->
    </div>
    <!-- /wp:core/column -->
</div>
<!-- /wp:core/columns -->