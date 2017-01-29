<?php
/* Wrote my awesome functions below */

function h7lc_sidebar_submenu () {
}

// Return an array with the menu item object ids of
// the current selected and all ancestors (up to 0/the root).
function h7lc_current_menu_item_parents() {
  $current_menu_item_ancestors = array();
  // Identify current menu item
  $menu_items = wp_get_nav_menu_items( 'top-de' );
  $current_menu_item = current( wp_filter_object_list( $menu_items, array( 'object_id' => get_queried_object_id() ) ) );

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
  $current_menu_item = current( wp_filter_object_list( $menu_items, array( 'object_id' => get_queried_object_id() ) ) );
  return $current_menu_item;
}
