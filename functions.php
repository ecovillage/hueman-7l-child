<?php
/* Wrote my awesome functions below */


// Return the current URL.  Please improve my worldview by pointing me to
// the actual implementation in wordpress core if you find it.
function h7lc_current_url() {
  return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
}


function h7lc_sidebar_submenu() {
  // current displayed item
  // list of parents
  // menu
  // cropped menu
}

// Return an array with the menu item object ids of
// the current selected and all ancestors (up to 0/the root).
function h7lc_current_menu_item_ancestors() {
  $current_menu_item_ancestors = array();
  // Identify current menu item
  $menu_items = wp_get_nav_menu_items( 'top-de' );
  // Not perfectly optimized (h7lc_current_menu_item does a menu_items query
  // too), but DRYer.
  $current_menu_item = h7lc_current_menu_item();

  // If current page not in the menu, freak out a bit, TODO but show main menu.
  // Also, this might mean we look at an archive
  if (!is_object($current_menu_item)) {
    return $current_menu_item_ancestors;
  }

  $current_menu_item_ancestors[] = $current_menu_item->ID;
  $current_menu_item_ancestors[] = $current_menu_item->menu_item_parent;

  // While we did not yet hit the root... ...go up.
  while ($current_menu_item_ancestors[count($current_menu_item_ancestors) - 1 ] != '0') {
    $last_item = $current_menu_item_ancestors[count($current_menu_item_ancestors) -1];
    $parent_menu_item = current( wp_filter_object_list( $menu_items, array( 'ID' => $last_item ) ) );
    $current_menu_item_ancestors[] = $parent_menu_item->menu_item_parent;
  }

  return $current_menu_item_ancestors;
}

function h7lc_childful_menu_item_ids() {
  $parent_ids = array();
  // Identify current menu item
  $menu_items = wp_get_nav_menu_items( 'top-de' );
  foreach ( $menu_items as $item ) {
    $parent_ids[] = $item->menu_item_parent;
  }
  return $parent_ids;
}

function h7lc_current_menu_item() {
  // Identify current menu item
  $menu_items = wp_get_nav_menu_items( 'top-de' );
  // Alternative: get_queried_object.
  $current_menu_item = current( wp_filter_object_list( $menu_items,
    array( 'object_id' => get_queried_object_id() ) ) );
  // If not found, check for url match.
  if ($current_menu_item == false) {
    $current_menu_item = current( wp_filter_object_list( $menu_items,
      array( 'url' => h7lc_current_url() ) ) );
  }
  return $current_menu_item;
}

  function load_flexslider_js() {
    //  if ( is_page_template( 'template-registration-page.php' ) ) {
    if (1 == 1) {
      wp_enqueue_script(
        'flexslider',
        get_template_directory_uri() . '/assets/front/js/lib/jquery.flexslider.min.js',
        array( 'jquery' ),
        '',
        false
      );
    }
  }

add_action( 'wp_enqueue_scripts', 'load_flexslider_js' );

