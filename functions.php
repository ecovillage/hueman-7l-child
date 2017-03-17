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

function h7lc_shortcode_featured_flexslider() {
  ob_start();
  get_template_part('parts/featured_custom');
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_shortcode( 'featured_news', 'h7lc_shortcode_featured_flexslider' );

// Shortcode to output the next 10 events in our style.
function h7lc_shortcode_upcoming_events() {
  ob_start();
  // TODO this is copy-and-paste code from ev7l widgets event-list-alx.php
  // -> extract it.
  $events = upcoming_events();

  if ( $events->have_posts() ) {
    echo '<div class="widget widget_ev7l_event_list_widget">';
    // move separate uls into loop
    echo '<ul class="ev7l_event_list alx-posts group thumbs-enabled">';
    $month = -1;
    $year = -1;
    global $post;
    while ( $events->have_posts() ) {
      $events->the_post();
      // New month?
      $start_month = date_i18n('F', get_post_meta($post->ID, 'fromdate', true));
      if ($month != $start_month) {
        $month = $start_month;
        echo '</ul>';
        echo '<h2 class="event-list-month-name">' . $month . '</h2>';
        echo '<ul class="ev7l_event_list alx-posts group thumbs-enabled">';
      }
      ?>
        <li>
          <div class="post-item-thumbnail">
            <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
            <?php hu_the_post_thumbnail('thumb-medium'); ?>
          </div>
          <div class="post-item-inner group">
            <p class="post-item-category"><a href="" rel="category tag">Veranstaltung</a>
            </p>
            <p class="post-item-title">
              <a href="<?php echo get_permalink($post->ID); ?>" rel="bookmark" title="<?php echo get_the_title($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a>
            </p>
            <p class="post-item-date">
              <?php echo date_i18n('D, d.m', get_post_meta($post->ID, 'fromdate', true)); ?>
              -
              <?php echo date_i18n('D, d.m.Y', get_post_meta($post->ID, 'todate', true)); ?>
            </p>
          </div>
        </li>
     <?php
    } // while $events->have_posts()
    echo '</ul>';
    echo '</div>';

    wp_reset_postdata();
  } // if $events->have_posts()
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_shortcode( 'upcoming_events', 'h7lc_shortcode_upcoming_events' );

function h7lc_shortcode_pages_list($atts) {
  $a = shortcode_atts( array('parent_name' => 'Projekte'), $atts );
  //$parent_page = get_page_by_title($a['parent_name']);
  $children_query = new WP_Query(
    array('post_type' => 'page', 'posts_per_page' => '-1', 'post_parent' => 1870));//$parent_page->ID));
  $children_query->have_posts();

  // From archive.php :
  ob_start();

?>
    <?php if ( $children_query->have_posts() ) : ?>

      <?php if ( hu_is_checked('blog-standard') ): ?>
        <?php while ( $children_query->have_posts() ): $children_query->the_post(); ?>
          <?php get_template_part('content-standard'); ?>
        <?php endwhile; ?>
      <?php else: ?>
      <div class="post-list group">
        <?php echo '<div class="post-row">'; while ( $children_query->have_posts() ): $children_query->the_post(); ?>
          <?php get_template_part('content'); ?>
        <?php if( ( $children_query->current_post + 1 ) % 2 == 0 ) { echo '</div><div class="post-row">'; }; endwhile; echo '</div>'; ?>
      </div><!--/.post-list-->
      <?php endif; ?>

      <!--?php get_template_part('parts/pagination'); ?-->

      <?php endif; ?><?php

  $ret = ob_get_contents();
  ob_end_clean();
  wp_reset_query();
  return $ret;
}

add_shortcode( 'pages_list', 'h7lc_shortcode_pages_list' );
