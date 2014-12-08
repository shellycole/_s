<?php
/**
 * Custom membership functions, if membership is needed.
 * You'll also need to uncomment the metaboxes in inc/redux-config.php
 *
 * @package _s
 */

// functionality to show/hide custom links in nav menus, based on whether
// or not the end user is logged in.
// in the nav menu, set a class of "members_only" to the item to apply this filter.
function _s_custom_nav_menu_items( $sorted_menu_items, $args=array() ) {
  
  $args = (array) $args;

  // Get menu id
  if ( $args['menu_id'] )
    $menu_id = $args['menu_id'];
  elseif ( $args['theme_location'] ) {
    $menu_locations = get_nav_menu_locations();
    $menu_id = $menu_locations[$args['theme_location']];
  }
  else {
    $nav_item_db_id = $sorted_menu_items[1]->ID;
    $nav_menu = wp_get_object_terms( $nav_item_db_id, 'nav_menu' );
    $menu_id = $nav_menu[0]->term_id;
  }
  
  // Get the class to exclude
  if ( ! is_user_logged_in() ) {
    $exclusion_class = 'members_only';  // class to exclude members links from non logged-in users
  }
  
  $modified_nav_items = array();
  
  // Cycle through all nav_items
  foreach ( $sorted_menu_items as $nav_item ) { 
    
    // Cycle through all classes
    for ( $i=0; $i < count( $nav_item->classes ); $i++ ) {
      $exclude = false;
      
      // if nothing is there set to add
      if ( strlen ( $nav_item->classes[ $i ] ) < 1 ) {
        $exclude = false;
      }
      else
      { 
        // if matches add to exclusion array & break loop
        if ( $nav_item->classes[ $i ] == $exclusion_class ) {
          $excluded_nav_items[] = $nav_item; 
          $exclude = true;
          break;
        }
      }
    }
    
    if ( $exclude != true )
      $modified_nav_items[] = $nav_item;
              
  }

  return $modified_nav_items;
}
//add_filter( 'wp_nav_menu_objects', '_s_custom_nav_menu_items' , 90 , 2 );