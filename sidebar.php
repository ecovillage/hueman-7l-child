<?php $layout = hu_get_layout_class(); ?>
<?php if ( $layout != 'col-1c'): ?>

	<div class="sidebar s1 collapsed" data-position="<?php echo hu_get_sidebar_position( 's1' ); ?>" data-layout="<?php echo $layout ?>" data-sb-id="s1">


		<a class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fa icon-sidebar-toggle"></i></a>

		<div class="sidebar-content">

			<?php if ( hu_is_checked('sidebar-top') ): ?>
  			<div class="sidebar-top group">
          <?php if ( hu_has_social_links() ) : ?>
              <p><?php _e('Follow:','hueman'); ?></p>
          <?php else : //if not customizing, display an empty p for design purposes ?>
              <?php if ( ! hu_is_customizing() && is_user_logged_in() && current_user_can( 'edit_theme_options' ) && is_super_admin() ) : ?>
                  <?php
                    printf( '<p style="text-transform:none;font-size: 0.8em;">%1$s. <a style="color: white;text-decoration:underline;" href="%2$s" title="%3$s">%3$s &raquo;</a></p>',
                        __('You can set your social links here from the live customizer', 'hueman'),
                        admin_url( 'customize.php?autofocus[section]=social_links_sec' ),
                        __('Customize now', 'hueman')
                    );
                  ?>
              <?php elseif ( ! is_user_logged_in() ) : ?>
                  <?php printf('<p>&nbsp;</p>'); ?>
              <?php endif; ?>
          <?php endif; ?>

          <?php hu_print_social_links() ; ?>
  			</div>
			<?php endif; ?>

      <?php if ( hu_get_option( 'post-nav' ) == 's1') { get_template_part('parts/post-nav'); } ?>

<!-- Leaving vanilla 3.3.4 hueman theme -->
      <?php if( true || is_page_template('page-templates/child-menu.php') ): ?>

<?php $current_menu_item_ancestors = h7lc_current_menu_item_ancestors(); ?>
<?php $current_menu_item           = h7lc_current_menu_item(); ?>
<?php $parent_ids                  = h7lc_childful_menu_item_ids(); ?>
<!--has title and url, and id, menu_item_parent-->

<?php
// Find path from current 'page' up to root of menu
// Count depth, (use two max)

echo "<ul class=\"child-menu group\">";

$items = wp_get_nav_menu_items( 'top-de' );

// Get root items (they dont have children)
$root_items = array_filter($items, function($k) { return !$k->menu_item_parent; });

foreach ( $root_items as $root_item ) {
  // Put only the oldest ancestor.
  if ( in_array($root_item->ID, $current_menu_item_ancestors ) ) {
    $item_id = $root_item->ID;
    $url     = $root_item->url;
    $title   = $root_item->title;
    $has_children = in_array($item_id, $parent_ids);
    $children_class = ($has_children ? " page_item_has_children " : "");

    echo "<li class=\"page_item current_page_ancestor current_page_parent".$children_class."\">";

    $children = array_filter($items, function($k) use (&$item_id) {
      return $k->menu_item_parent == $item_id;
    });

    $a_childless_class = (!$has_children) ? 'childless' : '';
    echo "  <a class=\"".$a_childless_class."\" href=\"".$url."\">".$title."</a>";

    // Then li children
    echo "<ul class=\"children\">";
    foreach ($children as $child) {
      $current_page_class = ($child->ID == $current_menu_item->ID) ? ' current_page_item ' : '';
      $current_page_ancestor_class = (in_array($child->ID, $current_menu_item_ancestors)) ? ' current_page_ancestor ' : '';
      echo "<li class=\"page_item page_item_".$child->ID.$current_page_class.$current_page_ancestor_class."\">";
      if (!in_array($child->ID, $parent_ids)) {
        echo "  <a class=\"childless\" href=\"".$child->url."\">".$child->title."</a>";
        echo "</li>";
      } else {
        echo "  <a href=\"".$child->url."\">".$child->title."</a>";
        // And again, last time, go down into children
        // But show these only if child is current page
        // (then the child li has page_item page-item-1859 page_item_has_children current_page_ancestor current_page_parent
        // and the grandchild + current_page_item
        echo "<ul class=\"children\">";
        $child_id = $child->ID;
        $grand_children = array_filter($items, function($k) use (&$child_id) {
          return $k->menu_item_parent == $child_id;
        });
        foreach ($grand_children as $grandchild) {
          // If grandchild is current_page add the style
          $current_page_class = ($grandchild->ID == $current_menu_item->ID) ? ' current_page_item ' : '';
          echo "<li class=\"page_item page_item_".$grandchild->ID.$current_page_class."\">";
          echo "  <a href=\"".$grandchild->url."\">".$grandchild->title."</a>";
          echo "</li>";
        }
        echo "</ul>";
        echo "</li>";
      }
    }
    echo "</ul>";
    echo "</li>";
  }
}
echo "</ul>";
?>
<!--
<ul class="child-menu group">
  <li class="page_item page-item-2 page_item_has_children">
    <a href="http://wp_felix.7l/de/beispiel-seite/">Beispiel-Seite</a>
    <ul class='children'>
      <li class="page_item page-item-1740">
        <a href="http://wp_felix.7l/de/beispiel-seite/entertitlehere/">EnterTitleHere</a></li>
    </ul>
  </li>
  <li class="page_item page-item-1747 page_item_has_children current_page_ancestor current_page_parent">
    <a href="http://wp_felix.7l/de/first-left/">First Left</a>
    <ul class='children'>
      <li class="page_item page-item-1755">
        <a href="http://wp_felix.7l/de/first-left/second-left/">Second Left</a></li>
      <li class="page_item page-item-1757">
        <a href="http://wp_felix.7l/de/first-left/second-left-2/">Second Left 2</a></li>
      <li class="page_item page-item-1759 page_item_has_children current_page_item">
        <a href="http://wp_felix.7l/de/first-left/second-left-2-wichi/">Second Left 2 wichi</a>
        <ul class='children'>
          <li class="page_item page-item-1761">
            <a href="http://wp_felix.7l/de/first-left/second-left-2-wichi/third-one/">Third one</a></li>
          <li class="page_item page-item-1763">
            <a href="http://wp_felix.7l/de/first-left/second-left-2-wichi/third-3/">Third 3</a></li>
        </ul>
      </li>
    </ul>
  </li>
  <li class="page_item page-item-1752">
    <a href="http://wp_felix.7l/de/first-right/">First right</a></li>
  <li class="page_item page-item-1750">
    <a href="http://wp_felix.7l/de/first-middel/">First Middel</a></li>
</ul>
-->
			<?php endif; ?>

<!-- Re-entering vanilla 3.3.4 hueman theme -->
      <?php if( is_page_template('page-templates/child-menu.php') ): ?>

			<ul class="child-menu group">
        <?php wp_list_pages('title_li=&sort_column=menu_order&depth=3'); ?>
			</ul>
			<?php endif; ?>

			<?php hu_print_widgets_in_location('s1') ?>

		</div><!--/.sidebar-content-->

	</div><!--/.sidebar-->

	<?php
    if ( in_array( $layout, array('col-3cm', 'col-3cl', 'col-3cr' ) ) ) {
      get_template_part('sidebar-2');
    }
	?>

<?php endif; ?>
