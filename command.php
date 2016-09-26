<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * List media sizes registered with WordPress.
 *
 * ```
 * $ wp media sizes
 * +---------------------------+-------+--------+-------+
 * | name                      | width | height | crop  |
 * +---------------------------+-------+--------+-------+
 * | full                      |       |        | false |
 * | twentyfourteen-full-width | 1038  | 576    | true  |
 * | large                     | 1024  | 1024   | true  |
 * | post-thumbnail            | 672   | 372    | true  |
 * | medium                    | 300   | 300    | true  |
 * | thumbnail                 | 150   | 150    | true  |
 * +---------------------------+-------+--------+-------+
 * ```
 *
 * [--fields=<fields>]
 * : Limit the output to specific fields. Defaults to all fields.
 *
 * [--format=<format>]
 * : List callbacks as a table, JSON, CSV, or YAML.
 * ---
 * default: table
 * options:
 *   - table
 *   - json
 *   - csv
 *   - yaml
 * ---
 */
$media_sizes_command = function( $_, $assoc_args ) {
	global $_wp_additional_image_sizes;

	$assoc_args = array_merge( array(
		'fields'      => 'name,width,height,crop'
	), $assoc_args );

	$sizes = array(
		array(
			'name'    => 'large',
			'width'   => intval( get_option( 'large_size_w' ) ),
			'height'  => intval( get_option( 'large_size_h' ) ),
			'crop'    => 'true',
		),
		array(
			'name'    => 'medium_large',
			'width'   => intval( get_option( 'medium_large_size_w' ) ),
			'height'  => intval( get_option( 'medium_large_size_h' ) ),
			'crop'    => 'true',
		),
		array(
			'name'    => 'medium',
			'width'   => intval( get_option( 'medium_size_w' ) ),
			'height'  => intval( get_option( 'medium_size_h' ) ),
			'crop'    => 'true',
		),
		array(
			'name'    => 'thumbnail',
			'width'   => intval( get_option( 'thumbnail_size_w' ) ),
			'height'  => intval( get_option( 'thumbnail_size_h' ) ),
			'crop'    => 'true',
		),
	);
	foreach( $_wp_additional_image_sizes as $size => $size_args ) {
		$sizes[] = array(
			'name'     => $size,
			'width'    => $size_args['width'],
			'height'   => $size_args['height'],
			'crop'     => $size_args['crop'] ? 'true' : 'false',
		);
	}
	usort( $sizes, function( $a, $b ){
		if ( $a['width'] == $b['width'] ) {
			return 0;
		}
		return ( $a['width'] < $b['width'] ) ? 1 : -1;
	});
	array_unshift( $sizes, array(
			'name'    => 'full',
			'width'   => '',
			'height'  => '',
			'crop'    => 'false',
	) );
	WP_CLI\Utils\format_items( $assoc_args['format'], $sizes, explode( ',', $assoc_args['fields'] ) );
};
WP_CLI::add_command( 'media sizes', $media_sizes_command );
