<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
* ------------------------------------------------------------------------- */

	// Add your custom functions here, or overwrite existing ones. Read more how to use:
	// http://codex.wordpress.org/Child_Themes


/* Enqueue parent and child theme styles*/
add_action( 'wp_enqueue_scripts', 'childtheme_enqueue_parent_style' );
function childtheme_enqueue_parent_style() {
    // Parent-Stylesheet laden
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    // Child-Stylesheet anschließend laden
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}


/** START Flexslider News Start **/

  /* TODO This needs more precision, we do not always need the flexslider js, but
    * for pages that use the featured posts feature ... */
  function load_flexslider_js() {
    //  if ( is_page_template( 'template-registration-page.php' ) ) {
    if (1 == 1) {
      wp_enqueue_script(
        'flexslider',
        get_template_directory_uri() . '/assets/front/js/libs/jquery.flexslider.js',
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
    get_template_part('parts/featured');
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
  }

  add_shortcode('featured_news', 'h7lc_shortcode_featured_flexslider' );

/** END Flexslider News Startseite **/


/** START Submenu in sidebar.php **/

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

/** END Submenu in sidebar.php **/

/* ------------------------------------------------------------------------- *
 *  Customizer: Widget-Bereiche auch ohne manage_options speichern
 * ------------------------------------------------------------------------- */
// Problem: Nutzer ohne Admin-Rechte (z. B. "Martin", Rolle "Theme-Editor" mit
// edit_theme_options) konnten im Customizer Änderungen am Menü einer Seite nicht
// speichern: "Das Speichern ist aufgrund 1 ungültiger Einstellung nicht möglich."
//
// Ursache: Das Hueman-Pro-Theme registriert seine Optionen standardmäßig mit der
// Berechtigung manage_options (hueman-pro/functions/czr/class-czr-init.php,
// hu_customize_arguments()). Die Oberfläche "Widget-Bereiche verwalten"
// (hu_theme_options[sidebar-areas]) markiert ihren Wert beim Laden des
// Customizers als geändert und schickt ihn deshalb bei JEDEM Speichern mit,
// auch wenn niemand die Widget-Bereiche angefasst hat. Ohne manage_options
// lehnt WordPress das als "unauthorized" ab, und der ganze Speichervorgang scheitert.
//
// Lösung: Nur für diese eine Einstellung reicht jetzt edit_theme_options (die
// übliche Berechtigung für Menüs, Widgets und Customizer). So müssen wir
// Redakteuren nicht manage_options geben, das Zugriff auf alle
// Einstellungsseiten bedeuten würde (bis hin zur Standardrolle bei der Registrierung).
// Priorität PHP_INT_MAX, damit das Theme die Einstellung vorher registriert hat.
add_action( 'customize_register', 'sl_sidebar_areas_capability', PHP_INT_MAX );
function sl_sidebar_areas_capability( $wp_customize ) {
    $setting = $wp_customize->get_setting( 'hu_theme_options[sidebar-areas]' );
    if ( $setting ) {
        $setting->capability = 'edit_theme_options';
    }
}
