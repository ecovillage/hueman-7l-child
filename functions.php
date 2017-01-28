<?php
/* Wrote my awesome functions below */

function h7lc_sidebar_submenu () {
}

function h7lc_current_menu_item_parents() {
  $current_menu_item_parents = array();
  // Identify current menu item
  $menu_items = wp_get_nav_menu_items( 'top-de' );
  $current_menu_item = current( wp_filter_object_list( $menu_items, array( 'object_id' => get_queried_object_id() ) ) );

  $current_menu_item_parents[] = $current_menu_item->ID;
  $current_menu_item_parents[] = $current_menu_item->menu_item_parent;

  while ($current_menu_item_parents[count($current_menu_item_parents) - 1 ] != '0') {
    $last_item = end($current_menu_item_parents);
    $parent_menu_item = current( wp_filter_object_list( $menu_items, array( 'ID' => $last_item ) ) );
    $current_menu_item_parents[] = $parent_menu_item->menu_item_parent;
  }

  return $current_menu_item_parents;
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
  $menu_items = wp_get_nav_menu_items( 't' );
  $current_menu_item = current( wp_filter_object_list( $menu_items, array( 'object_id' => get_queried_object_id() ) ) );
  return $current_menu_item;
}
