<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * List media sizes registered with WordPress.
 */
$media_sizes_command = function() {
	WP_CLI::success( "Hello world." );
};
WP_CLI::add_command( 'media sizes', $media_sizes_command );
