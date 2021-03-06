<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package _s
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function _s_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', '_s_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function _s_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', '_s_body_classes' );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function _s_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', '_s_wp_title', 10, 2 );
endif;

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function _s_render_title() {
		echo '<title>' . wp_title( '|', false, 'right' ) . "</title>\n";
	}
	add_action( 'wp_head', '_s_render_title' );
endif;

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function _s_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', '_s_setup_author' );


/**
 * When the end user hits the 404 page, it turns the page in the browser's URL
 * into a search query, to provide a better 404 page.
 */
function _s_search404($search_term_q) {
	/* run the url as a query */
	query_posts('s='. $search_term_q );
	if ( have_posts() ) :
	/* check to see if there are posts, before telling people something that isn't true */ ?>
	<div id="results">
	<?php $n = 1;
	    while ( have_posts() ) : the_post(); ?>
	    <div class="post">
		  <h5 class="entry-title"><?php echo $n; ?>. <a href="<?php the_permalink() ?>" title="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a></h5>
		  <p class="date"><?php the_time('F j, Y') ?></p>
		  <div class="entry">
			<?php the_excerpt(); ?>
		  </div>
		</div>
	<?php $n++; endwhile; ?>
	</div>
	<?php /* close the list before closing the if/else */
	else : endif;
}

/**
 * Add the search box to the nav menu
 */
function _s_add_search_box($items, $args) { 
        ob_start();
        get_search_form(); 
        $searchform = ob_get_contents();
        ob_end_clean();
 
        $items .= '<li class="searchform">' . $searchform . '</li>';
 
    return $items;
}
// add_filter('wp_nav_menu_items','_s_add_search_box', 10, 2);

/**
 * Get Media ID from the url path
 */
function _s_get_attachment_id_from_src($image_src) {
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
    $id = $wpdb->get_var($query);
    return $id;
}

/**
 * Get Page ID by slug
 */
function _s_get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

/**
 * Custom default avatar.  Requires you to upload an image 
 * names "custom-avatar.png" to the images directory.
 */
function _s_add_custom_default_gravatar( $avatar_defaults ) {
    $myavatar = get_template_directory() . '/images/custom-avatar.png';
    $avatar_defaults[$myavatar] = 'NAME HERE'; // name your avatar
    return $avatar_defaults;
}
//add_filter( 'avatar_defaults', '_s_add_custom_default_gravatar' );