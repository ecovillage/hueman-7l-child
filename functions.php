<?php

// Load the upcoming dates shortcode
require_once( get_stylesheet_directory() . '/includes/shortcodes.php' );

// Register query modifications for seminardesk
require_once( get_stylesheet_directory() . '/includes/sd_queries.php' );

// Load translation files from your child theme instead of the parent theme
function load_hueman_7l_child_theme_locale() {
  load_child_theme_textdomain( 'hueman-7l-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'load_hueman_7l_child_theme_locale' );

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

function h7lc_first_menu_id() {
  $theme_nav_menu_locations = get_theme_mod( 'nav_menu_locations' );
  return reset($theme_nav_menu_locations);
}

function h7lc_first_menus_items() {
  return wp_get_nav_menu_items( h7lc_first_menu_id() );
}


// Return an array with the menu item object ids of
// the current selected and all ancestors (up to 0/the root).
function h7lc_current_menu_item_ancestors() {
  $current_menu_item_ancestors = array();
  // All menu items
  $menu_items = h7lc_first_menus_items();

  // Identify current menu item
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
  $menu_items = h7lc_first_menus_items();
  foreach ( $menu_items as $item ) {
    $parent_ids[] = $item->menu_item_parent;
  }
  return $parent_ids;
}

/**
 * Get the menu_item object of the currently shown page/post.
 * If no menu item refers to the current posts object_id,
 * try a url match.  If that also fails, check whether we are
 * looking at a single ev7l-event or sd_cpt_event (which are not all listed in
 * any  menu).  If so, let the current menu item be a fixed one.
 *
 * If no one is found, default to the top page.
 */
function h7lc_current_menu_item() {
  // Identify current menu item
  $menu_items = h7lc_first_menus_items();
  // Alternative: get_queried_object.
  // Have a direct match: Current object is found in menu:
  $current_menu_item = current( wp_filter_object_list( $menu_items,
    array( 'object_id' => get_queried_object_id() ) ) );
  // If its an event, place some sensible default item (language dependent)

  if ($current_menu_item === false && get_post_type() == "ev7l-event" || get_post_type() == 'sd_cpt_event') {
    // FIXME get_locale? (in some conditions, lang is not defined),
    //   * alt: pll_current_language, get_bloginfo('language')

    if ( get_locale() == 'de_DE' || $lang == "de" ) {
      $current_menu_item = current( wp_filter_object_list( $menu_items,
        //array( 'object_id' => 1927 ) ) );
        array( 'title' => 'Rund um den Aufenthalt' ) ) );
    }
    else {
      $current_menu_item = current( wp_filter_object_list( $menu_items,
        array( 'title' => 'Visit us' ) ) );
    }
  }
  // If not found, check for url match:
  if ($current_menu_item == false) {
    $current_menu_item = current( wp_filter_object_list( $menu_items,
      array( 'url' => h7lc_current_url() ) ) );
  }
  // Choose referee overview page.
  if ($current_menu_item == false) {
    if (get_post_type() == "ev7l-referee") {
      $current_menu_item = current( wp_filter_object_list( $menu_items,
        array( 'title' => 'Referent*innen' ) ) );
    } else {
      // Default to start page/full menu
      $current_menu_item = $menu_items[0];
    }
  }

  return $current_menu_item;
}


  /* TODO This needs more precision, we do not always need the flexslider js, but
   * for pages that use the featured posts feature ... */
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

/** Load featured_custom partial to render news as a flexslider.
 * Currently, the categories and encoded languages are hardcoded.
 * This could be changed in the future by passing in a parameter. */
function h7lc_shortcode_featured_flexslider() {
  ob_start();
  get_template_part('parts/featured_custom');
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_shortcode('featured_news', 'h7lc_shortcode_featured_flexslider' );

/* Shortcode to output the next 10 events in our style. */
function h7lc_shortcode_upcoming_events() {
  ob_start();

  // TODO the template part is copy-and-paste code from ev7l widgets
  // event-list-alx.php -> extract/merge it
  global $upcoming_events;
  $upcoming_events = upcoming_events(10);

  get_template_part('parts/event_list_alx');

  wp_reset_postdata();
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_shortcode('upcoming_events', 'h7lc_shortcode_upcoming_events');

// Parameters in attr: 'parent_name' is the title of page whose
// children will be listed.
function h7lc_shortcode_pages_list($atts) {
  $a = shortcode_atts( array('parent_name' => 'Projekte'), $atts );
  //$parent_page = get_page_by_path( 'page-slug' );
  $parent_page = get_page_by_title($a['parent_name']);
  $children_query = new WP_Query(
    array('post_type' => 'page', 'posts_per_page' => '-1', 'post_parent' => $parent_page->ID));
  $children_query->have_posts();

  // From archive.php :
  ob_start();

  if ( $children_query->have_posts() ) { ?>
      <div class="post-list group">
        <?php echo '<div class="post-row">'; while ( $children_query->have_posts() ): $children_query->the_post(); ?>
          <?php get_template_part('content'); ?>
        <?php if( ( $children_query->current_post + 1 ) % 2 == 0 ) { echo '</div><div class="post-row">'; }; endwhile; echo '</div>'; ?>
      </div><!--/.post-list-->
      <!--?php get_template_part('parts/pagination'); ?-->
  <?php
  }
  $ret = ob_get_contents();
  ob_end_clean();
  wp_reset_query();
  return $ret;
}

add_shortcode('pages_list', 'h7lc_shortcode_pages_list');


/* Show a calender, built out of ul#calendar_events and parts/event_list_line
 * templates.
 * attrs can hold year and name (both as integers)
 * */
function h7lc_calendar($atts) {
  /* Fill with defaults. Use 'month' => date('m') to have the month populated. */
  ob_start();
  $a = shortcode_atts(array('year' => date('Y'), 'month' => false), $atts);
  $eventyear  = $a['year'];
  $eventmonth = $a['month'];
  if ($eventyear && !$eventmonth) {
    $events = events_in_year($eventyear);
    // TODO now we also want to split upcoming and past ....
    // there is ev7l functions for this already, but this should only apply to
    // the current year!
  } elseif ($eventmonth && !$eventyear) {
    $events = events_in_year_month(date('Y'), $eventmonth);
    // Should probably default to current year.  Can we default that in the get_query_var / eventyear assignment?
  } elseif ($eventmonth && $eventyear) {
    $events = events_in_year_month($eventyear, $eventmonth);
  } else {
    //echo 'Default (no year, no month)';
  }
  if ($events->have_posts() ) { ?>
    <ul class="calendar-events">
      <?php
      while ( $events->have_posts() ) {
        $events->the_post();
        get_template_part('parts/event_list_line');
      }
    echo '</ul>';
    $events->reset_postdata();
  } else {
    echo __("Keine Veranstaltungen im gewählten Zeitraum");
  }

  $ret = ob_get_contents();
  ob_end_clean();
  //wp_reset_postdata();
  wp_reset_query();
  return $ret;
}

add_shortcode('event_calendar', 'h7lc_calendar');


function h7lc_calendar_this_year_passed() {
  ob_start();
  $pevents = past_events_this_year();
  // TODO dry it up (extract render_calender_events ?)
  if ($pevents->have_posts() ) { ?>
    <ul class="calendar-events">
      <?php
      while ( $pevents->have_posts() ) {
        $pevents->the_post();
        get_template_part('parts/event_list_line');
      }
    echo '</ul>';
  } else {
    /* No events before today in this year. */
  }

  $ret = ob_get_contents();
  ob_end_clean();
  wp_reset_query();
  return $ret;
}

add_shortcode('event_calendar_this_year_past', 'h7lc_calendar_this_year_passed');

function h7lc_calendar_this_year_upcoming() {
  ob_start();
  $uevents = upcoming_events_this_year();
  // TODO dry it up (extract render_calender_events ?)
  if ($uevents->have_posts() ) { ?>
    <ul class="calendar-events">
      <?php
      while ( $uevents->have_posts() ) {
        $uevents->the_post();
        get_template_part('parts/event_list_line');
      }
    echo '</ul>';
  } else {
    /* No upcoming events this year. */
  }

  $ret = ob_get_contents();
  ob_end_clean();
  wp_reset_query();
  return $ret;
}

add_shortcode( 'event_calendar_this_year_upcoming', 'h7lc_calendar_this_year_upcoming');

function h7lc_show_registration_form($atts) {
  $a = shortcode_atts(array('eventuuid' => 0), $atts);

  // TODO: move the meta-data-extraction into
  // registration functions.
  $events = event_by_uuid($a['eventuuid']);
  $event  = $events->posts[0];

  global $event_fromdate;
  global $event_todate;
  $event_fromdate = date_i18n(__('D d. M. Y'), get_post_meta($event->ID, 'fromdate', true));
  $event_todate   = date_i18n(__('D d. M. Y'), get_post_meta($event->ID, 'todate', true));

  //wp_reset_postdata();

  ob_start();
  global $event_uuid;
  $event_uuid = $a['eventuuid'];
  # TODO: include might avoid redeclaration of functions (instead of get_tem...)
  get_template_part('parts/registration');
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_shortcode( 'event_registration_form', 'h7lc_show_registration_form');

function h7lc_load_code() {
  // This takes into account parent and child themes as well
  // alternative: require_once( get_stylesheet_directory() . '/inc/custom.php' );
  locate_template( array( 'inc/h7lc_settings.php' ), true, true );
}
add_action( 'after_setup_theme', 'h7lc_load_code' );


function h7lc_rubrik_query_limit( $query ) {
  if ( $query->is_main_query() && array_key_exists( 'sd_txn_labels', $query->query_vars ) ) {
    $query->set( 'posts_per_page', '-1' ); // -1 -> (all posts)
  }
}
add_action( 'pre_get_posts', 'h7lc_rubrik_query_limit' );




// Jens: remove added html if code from editor is pasted
remove_filter('the_content', 'wpautop');


// Jens: order category entries by date
function custom_order_by_date( $query ) {
    if ( is_category() && $query->is_main_query() ) {
        $query->set( 'order', 'DESC' ); // newest first
        $query->set( 'orderby', 'date' );
    }
}
add_action( 'pre_get_posts', 'custom_order_by_date' );
