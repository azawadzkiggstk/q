wp-env run cli "wp theme activate statik"
wp-env run tests-cli "wp theme activate statik"
wp-env run cli "wp option update statik_blocks_settings '{\"settings\":{\"save_js\":{\"value\":\"enable\"}}}' --format=json"
wp-env run tests-cli "wp option update statik_blocks_settings '{\"settings\":{\"save_js\":{\"value\":\"enable\"}}}' --format=json"
