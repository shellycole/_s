<?php
/**
 * Initialize the metabox class
 */
add_action( 'init', '_s_initialize_cmb_meta_boxes', 9999 );
function _s_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) ) {
		require_once( get_template_directory() . '/inc/cmb2/init.php' );
	}
}

/**
 * Set up metaboxes
 */
function _s_metaboxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'id' => 'theme_extras',
		'title' => 'Extras',
		'pages' => array( 'page', 'post' ), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Secondary content',
				'id' => _S_METABOX_PREFIX . 'secondary_content',
				// 'desc' => 'Anything you add in here will be displayed next to the logo.',
				'type' => 'wysiwyg'
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', '_s_metaboxes' );


/**
 * Retrieve a value from the metaboxes and display it
 * @param  string  $meta_name The id of the field
 * @param  boolean $wpautop   Whether or not to use the wpautop filter
 */
function _s_get_secondary_content( $meta_name, $wpautop = true ) {
	global $post;

	if ( $meta_content = get_post_meta( $post->ID, _S_METABOX_PREFIX . $meta_name, true ) ) {
		echo '<div class="secondary-content secondary-content-' . $meta_name . '" >' . "\r\n";
		echo ( $wpautop ) ? wpautop( $meta_content ) : $meta_content;
		echo '</div><!-- .secondary-content-' . $meta_name . ' -->' . "\r\n";
	}
}
