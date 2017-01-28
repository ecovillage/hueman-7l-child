<?php $layout = hu_layout_class(); ?>
<?php if ( $layout != 'col-1c'): ?>

	<div class="sidebar s1">

		<a class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fa icon-sidebar-toggle"></i></a>

		<div class="sidebar-content">

			<?php if ( hu_is_checked('sidebar-top') ): ?>
  			<div class="sidebar-top group">
          <p><?php echo hu_has_social_links() ? __('Follow:','hueman') : '&nbsp;' ; ?></p>
          <?php hu_print_social_links() ; ?>
  			</div>
			<?php endif; ?>

      <?php if ( hu_get_option( 'post-nav' ) == 's1') { get_template_part('parts/post-nav'); } ?>

<!-- Leaving vanilla 3.3.4 hueman theme -->
      <?php if( true || is_page_template('page-templates/child-menu.php') ): ?>

<?php $current_menu_item_parents = h7lc_current_menu_item_parents(); ?>
<?php $current_menu_item         = h7lc_current_menu_item(); ?>
<?php $parent_ids                = h7lc_childful_menu_item_ids(); ?>
<!--has title and url, and id, menu_item_parent-->

<?php
// Find path from current 'page' up to root of menu
// Count depth, (use two max)

echo "<ul class=\"child-menu group\">";

// TODO This needs honest refactoring.
$items = wp_get_nav_menu_items( 'top-de' );
//echo var_dump($items);
foreach ( $items as $root_item) {
  if ( !$root_item->menu_item_parent ) {
    $item_id = $root_item->ID;
    $url     = $root_item->url;
    $title   = $root_item->title;
    $has_children = in_array($item_id, $parent_ids);
    $children_class = ($has_children ? " page_item_has_children " : "");

    // Start a new thing, but only add class if its in list of ancesters
    if ( in_array($root_item->ID, $current_menu_item_parents ) ) {
      echo "<li class=\"page_item current_page_ancestor current_page_parent".$children_class."\">";
      echo "  <a href=\"".$url."\">".$title."</a>";
      // Then li children
      $children = array_filter($items, function($k) use (&$item_id) {
        return $k->menu_item_parent == $item_id;
      });
      echo "<ul class=\"children\">";
      foreach ($children as $child) {
        echo "<li class=\"page_item page_item_1704\">";
        echo "  <a href=\"".$child->url."\">".$child->title."</a></li>";
      }
      echo "</ul>";
      echo "</li>";
    }
  }
  // else {;} // Only walk over root items
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

<!-- Re-entering vanilla 3.3.4 hueman theme -->

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
