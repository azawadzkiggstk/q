-- This is an initial SQL file that is used for create each new Statik instance.
-- New instance will have set some initial values like Home page or Insights page.
-- Also, there are set some external Plugins values like Activation keys.
--
-- Version 3.0.0

-- -----------------------------------
-- Add a Home Page -------------------
-- -----------------------------------
INSERT INTO {{ DB_PREFIX }}posts (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt,
    post_status,
    comment_status,
    ping_status,
    post_password,
    post_name,
    to_ping,
    pinged,
    post_modified,
    post_modified_gmt,
    post_content_filtered,
    post_parent,
    guid,
    menu_order,
    post_type,
    post_mime_type,
    comment_count
) VALUES (
    1,
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"50px"}},"color":{"gradient":"linear-gradient(135deg,rgb(250,217,97) 0%,rgb(247,107,28) 100%)"}},"layout":{"inherit":true},"blockProps":{"className":"wp-block-group alignfull has-background","style":{"background":"linear-gradient(135deg,rgb(250,217,97) 0%,rgb(247,107,28) 100%)","paddingTop":"100px","paddingBottom":"50px"}},"blockId":"wxa32p"} -->
     <div class="wp-block-group alignfull has-background" style="background:linear-gradient(135deg,rgb(250,217,97) 0%,rgb(247,107,28) 100%);padding-top:100px;padding-bottom:50px"><!-- wp:columns {"blockProps":{"className":"wp-block-columns"},"blockId":"lgmids"} -->
     <div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"top","blockProps":{"className":"wp-block-column block-core-columns is-vertically-aligned-top"},"blockId":"1b5ey5o"} -->
     <div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"level":1,"blockProps":{},"blockId":"161swxz"} -->
     <h1><strong>Statik Demo Page</strong></h1>
     <!-- /wp:heading -->

     <!-- wp:paragraph {"style":{"typography":{"lineHeight":"2"}},"blockProps":{"style":{"lineHeight":"2"}},"blockId":"zordgr"} -->
     <p style="line-height:2">Sed eu libero non lorem imperdiet semper nec sit amet tortor. Vivamus vitae posuere est. Praesent sem lacus, congue vel fermentum eget, sodales sed ligula. Nulla posuere porttitor diam et pellentesque. Fusce hendrerit quam purus. Donec porta dictum elit vel lacinia. Suspendisse vitae sem metus.&nbsp;</p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column -->

     <!-- wp:column {"blockProps":{"className":"wp-block-column block-core-columns"},"blockId":"9zyxov"} -->
     <div class="wp-block-column"><!-- wp:image {"sizeSlug":"large","blockProps":{"className":"wp-block-image"},"blockId":"1mm2iul"} -->
     <figure class="wp-block-image size-large"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-1.webp" alt=""/></figure>
     <!-- /wp:image --></div>
     <!-- /wp:column --></div>
     <!-- /wp:columns --></div>
     <!-- /wp:group -->

     <!-- wp:statik/spacer {"blockProps":{"className":"wp-block-statik-spacer"},"blockId":"1widxju"} /-->

     <!-- wp:columns {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"blockProps":{"className":"wp-block-columns","style":{"marginTop":"0px","marginBottom":"0px"}},"blockId":"vv8lnh"} -->
     <div class="wp-block-columns" style="margin-top:0px;margin-bottom:0px"><!-- wp:column {"width":"100%","blockProps":{"className":"wp-block-column block-core-columns","style":{"flexBasis":"%"}},"blockId":"kel0yk"} -->
     <div class="wp-block-column"><!-- wp:paragraph {"fontSize":"large","blockProps":{"className":"has-large-font-size"},"blockId":"8xxg9n"} -->
     <p class="has-large-font-size"><meta charset="utf-8"><strong>Nullam vel nulla commodo</strong></p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column --></div>
     <!-- /wp:columns -->

     <!-- wp:columns {"blockProps":{"className":"wp-block-columns"},"blockId":"18l7h6a"} -->
     <div class="wp-block-columns"><!-- wp:column {"width":"33.33%","blockProps":{"className":"wp-block-column block-core-columns","style":{"flexBasis":"33.33%"}},"blockId":"1wkk3zc"} -->
     <div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:paragraph {"blockProps":{},"blockId":"17bw6ce"} -->
     <p>Aliquam lobortis purus eu erat tristique, eget semper mauris pulvinar. <meta charset="utf-8">Class aptent taciti soci</p>
     <!-- /wp:paragraph -->

     <!-- wp:buttons {"blockProps":{"className":"wp-block-buttons"},"blockId":"1l3wjaf"} -->
     <div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline","blockProps":{"className":"wp-block-button is-style-outline"},"blockId":"19n8yli"} -->
     <div class="wp-block-button is-style-outline"><a class="wp-block-button__link">Read more</a></div>
     <!-- /wp:button --></div>
     <!-- /wp:buttons --></div>
     <!-- /wp:column -->

     <!-- wp:column {"width":"66.66%","blockProps":{"className":"wp-block-column block-core-columns","style":{"flexBasis":"66.66%"}},"blockId":"x4jwhd"} -->
     <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph {"blockProps":{},"blockId":"903mp5"} -->
     <p>Nullam vel nulla commodo, dignissim magna vitae, placerat nulla. Donec pretium, eros eget auctor pulvinar, sem lorem viverra arcu, sed tristique orci risus viverra velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos velit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column --></div>
     <!-- /wp:columns -->

     <!-- wp:statik/spacer {"height":["50px"],"blockProps":{"className":"wp-block-statik-spacer"},"blockId":"1k1v1v7"} /-->

     <!-- wp:columns {"blockProps":{"className":"wp-block-columns"},"blockId":"2q9geb"} -->
     <div class="wp-block-columns"><!-- wp:column {"blockProps":{"className":"wp-block-column block-core-columns"},"blockId":"jeppyu"} -->
     <div class="wp-block-column"><!-- wp:statik/icon {"icon":"coin","iconSize":"50px","iconColor":"#72a603","blockProps":{"className":"wp-block-statik-icon has-block-align-center"},"blockId":"1viimk4"} /-->

     <!-- wp:paragraph {"align":"center","blockProps":{"id":"m-0"},"blockId":"7y259x"} -->
     <p class="has-text-align-center" id="m-0">Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. </p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column -->

     <!-- wp:column {"blockProps":{"className":"wp-block-column block-core-columns"},"blockId":"3saxtj"} -->
     <div class="wp-block-column"><!-- wp:statik/icon {"icon":"camera","iconSize":"50px","iconColor":"#224d73","blockProps":{"className":"wp-block-statik-icon has-block-align-center"},"blockId":"uvdanr"} /-->

     <!-- wp:paragraph {"align":"center","blockProps":{"id":"m-0"},"blockId":"1rcrf3q"} -->
     <p class="has-text-align-center" id="m-0">Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed</p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column -->

     <!-- wp:column {"blockProps":{"className":"wp-block-column block-core-columns"},"blockId":"1269nwa"} -->
     <div class="wp-block-column"><!-- wp:statik/icon {"icon":"calculator","iconSize":"50px","iconColor":"#734b02","blockProps":{"className":"wp-block-statik-icon has-block-align-center"},"blockId":"f8yiig"} /-->

     <!-- wp:paragraph {"align":"center","blockProps":{"id":"m-0"},"blockId":"qpm37d"} -->
     <p class="has-text-align-center" id="m-0"><meta charset="utf-8">Quisque cursus et, porttitor risus. Aliquam sem. Quisque cursus et, porttitor risus. Aliquam sem. In</p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column -->

     <!-- wp:column {"blockProps":{"className":"wp-block-column block-core-columns"},"blockId":"1qapxoi"} -->
     <div class="wp-block-column"><!-- wp:statik/icon {"icon":"fingerprint","iconSize":"50px","iconColor":"#8c6a03","blockProps":{"className":"wp-block-statik-icon has-block-align-center"},"blockId":"1dmx8l7"} /-->

     <!-- wp:paragraph {"align":"center","blockProps":{"id":"m-0"},"blockId":"1eu2czl"} -->
     <p class="has-text-align-center" id="m-0">Nullam wisi ultricies a, gravida vitae, dapibus risus ante sodales lectus blandit eu, tempor diam pede cursus</p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column --></div>
     <!-- /wp:columns -->

     <!-- wp:statik/spacer {"blockProps":{"className":"wp-block-statik-spacer"},"blockId":"e6x138"} /-->

     <!-- wp:group {"style":{"border":{"width":"1px","style":"dotted","radius":"24px","color":"#bbb7a9"},"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}},"color":{"gradient":"linear-gradient(237deg,rgb(188,10,45) 0%,rgb(48,11,21) 95%)"}},"layout":{"inherit":false},"blockProps":{"className":"wp-block-group has-border-color has-background","style":{"background":"linear-gradient(237deg,rgb(188,10,45) 0%,rgb(48,11,21) 95%)","borderColor":"#bbb7a9","borderRadius":"24px","borderStyle":"dotted","borderWidth":"1px","paddingTop":"20px","paddingRight":"20px","paddingBottom":"20px","paddingLeft":"20px"}},"blockId":"1vebyw7"} -->
     <div class="wp-block-group has-border-color has-background" style="background:linear-gradient(237deg,rgb(188,10,45) 0%,rgb(48,11,21) 95%);border-color:#bbb7a9;border-radius:24px;border-style:dotted;border-width:1px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:heading {"textAlign":"center","level":3,"textColor":"white","blockProps":{"className":"has-white-color has-text-color"},"blockId":"pnkls5"} -->
     <h3 class="has-text-align-center has-white-color has-text-color"><strong>See how your product will </strong><br><strong>revolutionize Your life!</strong></h3>
     <!-- /wp:heading -->

     <!-- wp:buttons {"className":"is-content-justification-center","layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"},"blockProps":{"className":"wp-block-buttons is-content-justification-center"},"blockId":"1jxoe7a"} -->
     <div class="wp-block-buttons is-content-justification-center"><!-- wp:button {"textColor":"white","className":"is-style-outline","blockProps":{"className":"wp-block-button is-style-outline"},"blockId":"18h0tid"} -->
     <div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color">Contact Us!</a></div>
     <!-- /wp:button --></div>
     <!-- /wp:buttons --></div>
     <!-- /wp:group -->

     <!-- wp:statik/spacer {"blockProps":{"className":"wp-block-statik-spacer"},"blockId":"is8dqh"} /-->

     <!-- wp:columns {"blockProps":{"className":"wp-block-columns"},"blockId":"rulkqe"} -->
     <div class="wp-block-columns"><!-- wp:column {"width":"50%","blockProps":{"className":"wp-block-column block-core-columns","style":{"flexBasis":"50%"}},"blockId":"15pjj4b"} -->
     <div class="wp-block-column" style="flex-basis:50%"><!-- wp:image {"sizeSlug":"large","className":"is-style-default","blockProps":{"className":"wp-block-image is-style-default"},"blockId":"u8vyfe"} -->
     <figure class="wp-block-image size-large is-style-default"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-10.webp" alt=""/></figure>
     <!-- /wp:image --></div>
     <!-- /wp:column -->

     <!-- wp:column {"verticalAlignment":"center","width":"50%","blockProps":{"className":"wp-block-column block-core-columns is-vertically-aligned-center","style":{"flexBasis":"50%"}},"blockId":"gdy01h"} -->
     <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:paragraph {"fontSize":"large","blockProps":{"className":"has-large-font-size"},"blockId":"1h673t8"} -->
     <p class="has-large-font-size"><meta charset="utf-8"><strong>Nullam vel nulla commodo</strong></p>
     <!-- /wp:paragraph -->

     <!-- wp:paragraph {"blockProps":{},"blockId":"1smuoi5"} -->
     <p><meta charset="utf-8">Nullam vel nulla commodo, dignissim magna vitae, placerat nulla.  Aliquam lobortis purus eu erat tristique, Duis rhoncus metus quis tincidunt sollicitudin. Proin nisi ante, eleifend vitae rhoncus vitae, ullamcorper sit amet leo.</p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column --></div>
     <!-- /wp:columns -->

     <!-- wp:columns {"isStackedOnMobile":false,"blockProps":{"className":"wp-block-columns is-not-stacked-on-mobile"},"blockId":"dmgpnf"} -->
     <div class="wp-block-columns is-not-stacked-on-mobile"><!-- wp:column {"verticalAlignment":"center","width":"50%","blockProps":{"className":"wp-block-column block-core-columns is-vertically-aligned-center","style":{"flexBasis":"50%"}},"blockId":"1q8kat5"} -->
     <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:paragraph {"fontSize":"large","blockProps":{"className":"has-large-font-size"},"blockId":"13b95fb"} -->
     <p class="has-large-font-size"><meta charset="utf-8"><strong>Nullam vel nulla commodo</strong></p>
     <!-- /wp:paragraph -->

     <!-- wp:paragraph {"blockProps":{},"blockId":"1erwq48"} -->
     <p><meta charset="utf-8">Ut dui purus, congue at lobortis sit amet, ultricies quis nunc. Integer rhoncus libero id congue dictum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. </p>
     <!-- /wp:paragraph --></div>
     <!-- /wp:column -->

     <!-- wp:column {"width":"50%","blockProps":{"className":"wp-block-column block-core-columns","style":{"flexBasis":"50%"}},"blockId":"25t4yi"} -->
     <div class="wp-block-column" style="flex-basis:50%"><!-- wp:image {"sizeSlug":"large","className":"is-style-default","blockProps":{"className":"wp-block-image is-style-default"},"blockId":"1pq9m8p"} -->
     <figure class="wp-block-image size-large is-style-default"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-9.webp" alt=""/></figure>
     <!-- /wp:image --></div>
     <!-- /wp:column --></div>
     <!-- /wp:columns -->

     <!-- wp:statik/spacer {"blockProps":{"className":"wp-block-statik-spacer"},"blockId":"p34acc"} /-->',
    'Home Page',
    '',
    'publish',
    'closed',
    'closed',
    '',
    'home-page',
    '',
    '',
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    0,
    '{{ DOMAIN }}/?p=',
    0,
    'page',
    '',
    0
);
SET @homepage_id = LAST_INSERT_ID();
UPDATE {{ DB_PREFIX }}posts SET guid = CONCAT('{{ DOMAIN }}/?p=', @homepage_id) WHERE ID = @homepage_id;

-- -------------------------------------
-- Add navigation menus ----------------
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}terms (term_id, name, slug, term_group)
VALUES
	(2, 'Main Menu', 'main-menu', 0),
	(3, 'Mobile Menu', 'mobile-menu', 0),
	(4, 'Footer Menu', 'footer-menu', 0);
INSERT INTO {{ DB_PREFIX }}term_taxonomy (term_taxonomy_id, term_id, taxonomy, description, parent, count)
VALUES
	(2, 2, 'nav_menu', '', 0, 2),
	(3, 3, 'nav_menu', '', 0, 2),
	(4, 4, 'nav_menu', '', 0, 0);
INSERT INTO {{ DB_PREFIX }}options (option_name, option_value, autoload)
VALUES
	('theme_mods_statik', 'a:1:{s:18:\"nav_menu_locations\";a:3:{s:7:\"primary\";i:2;s:6:\"mobile\";i:3;s:6:\"footer\";i:4;}}', 'yes')
ON DUPLICATE KEY UPDATE option_value = "a:1:{s:18:\"nav_menu_locations\";a:3:{s:7:\"primary\";i:2;s:6:\"mobile\";i:3;s:6:\"footer\";i:4;}}";

-- -------------------------------------
-- Add Home Page to Main Menu ----------
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}posts (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt,
    post_status,
    comment_status,
    ping_status,
    post_password,
    post_name,
    to_ping,
    pinged,
    post_modified,
    post_modified_gmt,
    post_content_filtered,
    post_parent,
    guid,
    menu_order,
    post_type,
    post_mime_type,
    comment_count
) VALUES (
    1,
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    '',
    '',
    'publish',
    'closed',
    'closed',
    '',
    '',
    '',
    '',
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    0,
    '{{ DOMAIN }}/?p=',
    1,
    'nav_menu_item',
    '',
    0
);
SET @menu_1_homepage = LAST_INSERT_ID();
UPDATE {{ DB_PREFIX }}posts SET guid = CONCAT('{{ DOMAIN }}/?p=', @menu_1_homepage), post_name = @menu_1_homepage WHERE ID = @menu_1_homepage;
INSERT INTO {{ DB_PREFIX }}postmeta (post_id, meta_key, meta_value)
VALUES
	(@menu_1_homepage, '_menu_item_type', 'post_type'),
	(@menu_1_homepage, '_menu_item_menu_item_parent', '0'),
	(@menu_1_homepage, '_menu_item_object_id', @homepage_id),
	(@menu_1_homepage, '_menu_item_object', 'page'),
	(@menu_1_homepage, '_menu_item_target', ''),
	(@menu_1_homepage, '_menu_item_classes', 'a:1:{i:0;s:0:\"\”;}'),
	(@menu_1_homepage, '_menu_item_xfn', ''),
	(@menu_1_homepage, '_menu_item_url', '');
INSERT INTO {{ DB_PREFIX }}term_relationships (object_id, term_taxonomy_id, term_order)
VALUES
	(@menu_1_homepage, 2, 0);

-- -------------------------------------
-- Add Home Page to Mobile Menu --------
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}posts (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt,
    post_status,
    comment_status,
    ping_status,
    post_password,
    post_name,
    to_ping,
    pinged,
    post_modified,
    post_modified_gmt,
    post_content_filtered,
    post_parent,
    guid,
    menu_order,
    post_type,
    post_mime_type,
    comment_count
) VALUES (
    1,
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    '',
    '',
    'publish',
    'closed',
    'closed',
    '',
    '',
    '',
    '',
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    0,
    '{{ DOMAIN }}/?p=',
    1,
    'nav_menu_item',
    '',
    0
);
SET @menu_2_homepage = LAST_INSERT_ID();
UPDATE {{ DB_PREFIX }}posts SET guid = CONCAT('{{ DOMAIN }}/?p=', @menu_2_homepage), post_name = @menu_2_homepage WHERE ID = @menu_2_homepage;
INSERT INTO {{ DB_PREFIX }}postmeta (post_id, meta_key, meta_value)
VALUES
	(@menu_2_homepage, '_menu_item_type', 'post_type'),
	(@menu_2_homepage, '_menu_item_menu_item_parent', '0'),
	(@menu_2_homepage, '_menu_item_object_id', @homepage_id),
	(@menu_2_homepage, '_menu_item_object', 'page'),
	(@menu_2_homepage, '_menu_item_target', ''),
	(@menu_2_homepage, '_menu_item_classes', 'a:1:{i:0;s:0:\"\”;}'),
	(@menu_2_homepage, '_menu_item_xfn', ''),
	(@menu_2_homepage, '_menu_item_url', '');
INSERT INTO {{ DB_PREFIX }}term_relationships (object_id, term_taxonomy_id, term_order)
VALUES
	(@menu_2_homepage, 3, 0);

-- -------------------------------------
-- Add Hello world content -------------
-- -------------------------------------
UPDATE {{ DB_PREFIX }}posts
    SET post_content = '<!-- wp:paragraph -->
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus libero est, ullamcorper at fermentum at, pellentesque sed nunc. Ut quis bibendum ante, non cursus augue. Etiam vel neque eget leo interdum fermentum vel et ex. Aliquam erat volutpat. Nunc mollis vehicula magna at varius. Nam nisi eros, venenatis ac leo ut, consequat finibus mi. Nam urna metus, mattis eget molestie nec, vulputate in sapien. Nam at tincidunt elit. Aliquam nec nunc at erat placerat scelerisque id nec sem. Duis cursus velit urna, ut laoreet elit interdum id. Sed sagittis, turpis ut vulputate hendrerit, justo tellus condimentum leo, nec facilisis dui magna non augue. Morbi sit amet convallis augue. Cras vitae porta ante, nec faucibus ante. Vivamus accumsan pellentesque justo et mollis.</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>Curabitur elementum dolor in elit consequat, non vehicula lectus maximus. <strong>Praesent at dui mollis</strong>, imperdiet libero ac, aliquam nisi. Integer malesuada tortor diam, vel placerat nunc mollis egestas. Nunc finibus efficitur nibh ac vestibulum. Nam pretium ex in lectus maximus efficitur. Nulla mollis massa viverra odio ornare, sit amet ullamcorper ex lobortis. Nulla facilisi. Vivamus vulputate et purus a consequat. Curabitur nibh libero, cursus nec porta ac, accumsan nec dui. Maecenas commodo ante in libero tempor, eget cursus lectus tincidunt. Pellentesque lorem augue, suscipit euismod orci vitae, ultrices lobortis nisl.</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>Proin sed eros libero. Pellentesque purus mauris, accumsan quis auctor vel, faucibus at arcu. Aenean consectetur posuere consequat. Proin vestibulum volutpat vehicula. Phasellus tempus rhoncus turpis vel tempor. Phasellus consectetur erat ac suscipit fermentum. Donec eleifend, est sed tristique suscipit, purus purus faucibus ipsum, vitae interdum ante risus et odio.</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>Maecenas semper magna dignissim metus eleifend pulvinar. Duis auctor turpis magna, quis porta quam lobortis dapibus. Sed porttitor tellus id sodales imperdiet. Vestibulum lobortis ligula nibh, vestibulum euismod augue volutpat quis. Vivamus molestie venenatis auctor. Ut tempor sem vitae nisi fringilla, sed tristique urna venenatis. Nulla mattis ipsum leo, ac rutrum metus posuere et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed posuere consectetur libero.</p>
        <!-- /wp:paragraph -->

        <!-- wp:statik/spacer {"height":["50px"],"blockProps":{"className":"wp-block-statik-spacer"}} /-->

        <!-- wp:image {"sizeSlug":"large"} -->
        <figure class="wp-block-image size-large"><img src="https://assets-storage-statik-press.s3-eu-west-1.amazonaws.com/statik-boilerplate-assets/web-asset-2.webp" alt=""/></figure>
        <!-- /wp:image -->

        <!-- wp:statik/spacer {"height":["50px"],"blockProps":{"className":"wp-block-statik-spacer"}} /-->

        <!-- wp:paragraph -->
        <p>Proin sed eros libero. Pellentesque purus mauris, accumsan quis auctor vel, faucibus at arcu. Aenean consectetur posuere consequat. Proin vestibulum volutpat vehicula. Phasellus tempus rhoncus turpis vel tempor. Phasellus consectetur erat ac suscipit fermentum. Donec eleifend, est sed tristique suscipit, purus purus faucibus ipsum, vitae interdum ante risus et odio.</p>
        <!-- /wp:paragraph -->

        <!-- wp:statik/spacer {"height":["50px"],"blockProps":{"className":"wp-block-statik-spacer"}} /-->'
    WHERE ID = 1;

-- -------------------------------------
-- Add Hello world to Main Menu --------
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}posts (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt,
    post_status,
    comment_status,
    ping_status,
    post_password,
    post_name,
    to_ping,
    pinged,
    post_modified,
    post_modified_gmt,
    post_content_filtered,
    post_parent,
    guid,
    menu_order,
    post_type,
    post_mime_type,
    comment_count
) VALUES (
    1,
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    '',
    '',
    'publish',
    'closed',
    'closed',
    '',
    '',
    '',
    '',
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    0,
    '{{ DOMAIN }}/?p=',
    2,
    'nav_menu_item',
    '',
    0
);
SET @menu_1_insights = LAST_INSERT_ID();
UPDATE {{ DB_PREFIX }}posts SET guid = CONCAT('{{ DOMAIN }}/?p=', @menu_1_insights), post_name = @menu_1_insights WHERE ID = @menu_1_insights;
INSERT INTO {{ DB_PREFIX }}postmeta (post_id, meta_key, meta_value)
VALUES
	(@menu_1_insights, '_menu_item_type', 'post_type'),
    (@menu_1_insights, '_menu_item_menu_item_parent', '0'),
    (@menu_1_insights, '_menu_item_object_id', '1'),
    (@menu_1_insights, '_menu_item_object', 'post'),
    (@menu_1_insights, '_menu_item_target', ''),
    (@menu_1_insights, '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),
    (@menu_1_insights, '_menu_item_xfn', ''),
    (@menu_1_insights, '_menu_item_url', '');
INSERT INTO {{ DB_PREFIX }}term_relationships (object_id, term_taxonomy_id, term_order)
VALUES
	(@menu_1_insights, 2, 0);

-- -------------------------------------
-- Add Hello world to Mobile Menu ------
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}posts (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt,
    post_status,
    comment_status,
    ping_status,
    post_password,
    post_name,
    to_ping,
    pinged,
    post_modified,
    post_modified_gmt,
    post_content_filtered,
    post_parent,
    guid,
    menu_order,
    post_type,
    post_mime_type,
    comment_count
) VALUES (
    1,
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    '',
    '',
    'publish',
    'closed',
    'closed',
    '',
    '',
    '',
    '',
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    0,
    '{{ DOMAIN }}/?p=',
    2,
    'nav_menu_item',
    '',
    0
);
SET @menu_2_insights = LAST_INSERT_ID();
UPDATE {{ DB_PREFIX }}posts SET guid = CONCAT('{{ DOMAIN }}/?p=', @menu_2_insights), post_name = @menu_2_insights WHERE ID = @menu_2_insights;
INSERT INTO {{ DB_PREFIX }}postmeta (post_id, meta_key, meta_value)
VALUES
	(@menu_2_insights, '_menu_item_type', 'post_type'),
    (@menu_2_insights, '_menu_item_menu_item_parent', '0'),
    (@menu_2_insights, '_menu_item_object_id', '1'),
    (@menu_2_insights, '_menu_item_object', 'post'),
    (@menu_2_insights, '_menu_item_target', ''),
    (@menu_2_insights, '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),
    (@menu_2_insights, '_menu_item_xfn', ''),
    (@menu_2_insights, '_menu_item_url', '');
INSERT INTO {{ DB_PREFIX }}term_relationships (object_id, term_taxonomy_id, term_order)
VALUES
	(@menu_2_insights, 3, 0);

-- -------------------------------------
-- Add Hello world to Footer Menu -----
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}posts (
    post_author,
    post_date,
    post_date_gmt,
    post_content,
    post_title,
    post_excerpt,
    post_status,
    comment_status,
    ping_status,
    post_password,
    post_name,
    to_ping,
    pinged,
    post_modified,
    post_modified_gmt,
    post_content_filtered,
    post_parent,
    guid,
    menu_order,
    post_type,
    post_mime_type,
    comment_count
) VALUES (
    1,
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    '',
    '',
    'publish',
    'closed',
    'closed',
    '',
    '',
    '',
    '',
    '{{ DATE_TIME }}',
    '{{ DATE_TIME }}',
    '',
    0,
    '{{ DOMAIN }}/?p=',
    2,
    'nav_menu_item',
    '',
    0
);
SET @menu_3_insights = LAST_INSERT_ID();
UPDATE {{ DB_PREFIX }}posts SET guid = CONCAT('{{ DOMAIN }}/?p=', @menu_3_insights), post_name = @menu_3_insights WHERE ID = @menu_3_insights;
INSERT INTO {{ DB_PREFIX }}postmeta (post_id, meta_key, meta_value)
VALUES
	(@menu_3_insights, '_menu_item_type', 'post_type'),
	(@menu_3_insights, '_menu_item_menu_item_parent', '0'),
	(@menu_3_insights, '_menu_item_object_id', '1'),
	(@menu_3_insights, '_menu_item_object', 'post'),
	(@menu_3_insights, '_menu_item_target', ''),
	(@menu_3_insights, '_menu_item_classes', 'a:1:{i:0;s:0:\"\";}'),
	(@menu_3_insights, '_menu_item_xfn', ''),
	(@menu_3_insights, '_menu_item_url', '');
INSERT INTO {{ DB_PREFIX }}term_relationships (object_id, term_taxonomy_id, term_order)
VALUES
	(@menu_3_insights, 4, 0);

-- -------------------------------------
-- Set blog name -----------------------
-- -------------------------------------
UPDATE {{ DB_PREFIX }}options SET option_value = 'Statik website' WHERE option_name = 'blogname';
UPDATE {{ DB_PREFIX }}options SET option_value = 'Your new awesome and fast Statik website' WHERE option_name = 'blogdescription';

-- -------------------------------------
-- Set user name -----------------------
-- -------------------------------------
UPDATE {{ DB_PREFIX }}users SET display_name = 'Infrastructure Team' WHERE ID = 1;
UPDATE {{ DB_PREFIX }}usermeta SET meta_value = 'Infrastructure Team' WHERE user_id = 1 AND meta_key = 'nickname';
UPDATE {{ DB_PREFIX }}usermeta SET meta_value = 'Infrastructure' WHERE user_id = 1 AND meta_key = 'first_name';
UPDATE {{ DB_PREFIX }}usermeta SET meta_value = 'Team' WHERE user_id = 1 AND meta_key = 'last_name';
UPDATE {{ DB_PREFIX }}usermeta SET meta_value = 'https://statik.space' WHERE user_id = 1 AND meta_key = 'url';

-- -------------------------------------
-- Set homepage ------------------------
-- -------------------------------------
UPDATE {{ DB_PREFIX }}options SET option_value = 'page' WHERE option_name = 'show_on_front';
UPDATE {{ DB_PREFIX }}options SET option_value = @homepage_id WHERE option_name = 'page_on_front';

-- -----------------------------------
-- Set Statik theme ------------------
-- -----------------------------------
UPDATE {{ DB_PREFIX }}options SET option_value = 'statik' WHERE option_name = 'template';
UPDATE {{ DB_PREFIX }}options SET option_value = 'statik' WHERE option_name = 'stylesheet';

-- -------------------------------------
-- Disable Search Engine Visibility ----
-- -------------------------------------
UPDATE {{ DB_PREFIX }}options SET option_value = 0 WHERE option_name = 'blog_public';

-- -------------------------------------
-- Correct permalinks ------------------
-- -------------------------------------
UPDATE {{ DB_PREFIX }}options SET option_value = '/insights/%postname%/' WHERE option_name = 'permalink_structure';

-- -------------------------------------
-- Install Gravity Forms plugin --------
-- -------------------------------------
INSERT INTO {{ DB_PREFIX }}options (option_name, option_value, autoload)
VALUES
	('rg_gforms_key', 'e8051851-5c44-4bbf-ba57-51a3592519c9', 'yes'),
	('gform_enable_noconflict', '1', 'yes'),
	('rg_gforms_currency', 'GBP', 'yes'),
	('rg_gforms_hideLicense', '1', 'yes'),
	('rg_gforms_organization', 'enterprise', 'yes');

-- -------------------------------------
-- Set Gravity Form API settings -------
-- -------------------------------------
UPDATE {{ DB_PREFIX }}options SET option_value = '' WHERE option_name = 'gform_pending_installation';
UPDATE {{ DB_PREFIX }}options SET option_value = '' WHERE option_name = 'gform_enable_noconflict';

CREATE TABLE {{ DB_PREFIX }}gf_rest_api_keys (
    key_id BIGINT UNSIGNED NOT NULL auto_increment,
    user_id BIGINT UNSIGNED NOT NULL,
    description varchar(200) NULL,
    permissions varchar(10) NOT NULL,
    consumer_key char(64) NOT NULL,
    consumer_secret char(43) NOT NULL,
    nonces longtext NULL,
    truncated_key char(7) NOT NULL,
    last_access datetime NULL default null,
    PRIMARY KEY  (key_id),
    KEY consumer_key (consumer_key),
    KEY consumer_secret (consumer_secret)
);
