<?php
// Load translation files from your child theme instead of the parent theme
function load_hueman_7l_child_theme_locale() {
  load_child_theme_textdomain( 'hueman-7l-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'load_hueman_7l_child_theme_locale' );
#load_theme_textdomain('hueman-7l-child');

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
  return reset(get_theme_mod( 'nav_menu_locations' ));
}

function h7lc_first_menus_items() {
  return wp_get_nav_menu_items( h7lc_first_menu_id() );
}


// Return an array with the menu item object ids of
// the current selected and all ancestors (up to 0/the root).
function h7lc_current_menu_item_ancestors() {
  $current_menu_item_ancestors = array();
  // Identify current menu item
  $menu_items = h7lc_first_menus_items();

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
 * looking at a single ev7l-event (which are not all listed in the
 * menu).  If so, let the current menu item be a fixed one.
 *
 * If no one is found, default to the top page.
 */
function h7lc_current_menu_item() {
  // Identify current menu item
  $menu_items = h7lc_first_menus_items();
  // Alternative: get_queried_object.
  $current_menu_item = current( wp_filter_object_list( $menu_items,
    array( 'object_id' => get_queried_object_id() ) ) );
  // If not found, check for url match.
  if ($current_menu_item == false) {
    $current_menu_item = current( wp_filter_object_list( $menu_items,
      array( 'url' => h7lc_current_url() ) ) );
  }
  if ($current_menu_item == false) {
    if (get_post_type() == "ev7l-event") {
      $current_menu_item = current( wp_filter_object_list( $menu_items,
        //array( 'object_id' => 1927 ) ) );
        array( 'title' => 'Rund um den Aufenthalt' ) ) );
    }
    else if (get_post_type() == "ev7l-referee") {
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

/** Load featured_custom partial to render news as a flexslider. */
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
add_shortcode('event_calendar_this_year_upcoming', 'h7lc_calendar_this_year_upcoming');

/*
add_filter('plugin_action_links', 'myplugin_plugin_action_links', 10, 2);

function myplugin_plugin_action_links($links, $file) {
      static $this_plugin;

          if (!$this_plugin) {
                    $this_plugin = plugin_basename(__FILE__);
                        }

          if ($file == $this_plugin) {
                    // The "page" query string value must be equal to the slug
            //         // of the Settings admin page we defined earlier, which in
            //                 // this case equals "myplugin-settings".
            //                         $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=myplugin-settings">Settings</a>';
            //                                 array_unshift($links, $settings_link);
            //                                     }
            //
            //                                         return $links;
            //                                         }
            //                                         */



function h7cl_load_code() {
  // This takes into account parent and child themes as well
  // alternative: require_once( get_stylesheet_directory() . '/inc/custom.php' );
  locate_template( array( 'inc/h7lc_settings.php' ), true, true );
}
add_action( 'after_setup_theme', 'h7cl_load_code');
