<?php $layout = hu_get_layout_class(); ?>
<?php if ( $layout != 'col-1c'): ?>

	<div class="sidebar s1 collapsed" data-position="<?php echo hu_get_sidebar_position( 's1' ); ?>" data-layout="<?php echo $layout ?>" data-sb-id="s1">

		<button class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fas sidebar-toggle-arrows"></i></button>

		<div class="sidebar-content">

			<?php if ( hu_is_checked('sidebar-top') ): ?>
         <?php
            // wp_kses_post() Sanitizes content for allowed HTML tags for post content.
            // see https://developer.wordpress.org/reference/functions/wp_kses_post/
            $sb_text = wp_kses_post( hu_get_option( 'primary-sb-text' ) );
          ?>
  			<div class="sidebar-top group">
          <?php if ( hu_has_social_links() || !empty( $sb_text ) ) : ?>
              <?php
                if ( !empty( $sb_text ) ) {
                    echo apply_filters( 'primary_sb_text', sprintf( '<p>%1$s</p>', hu_get_option( 'primary-sb-text' ) ) );
                }
              ?>
          <?php else : //if not customizing, display an empty p for design purposes ?>
              <?php if ( hu_user_can_see_customize_notices_on_front() ) : ?>
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
          <?php
            if ( hu_is_checked('sl-in-sidebar') ) {
                hu_print_social_links() ;
            }
          ?>
  			</div>
			<?php endif; ?>

<!-- Leaving vanilla 3.7.25 hueman theme -->
  <?php $current_menu_item_ancestors = h7lc_current_menu_item_ancestors(); ?>
  <?php $current_menu_item           = h7lc_current_menu_item(); ?>
  <?php $parent_ids                  = h7lc_childful_menu_item_ids(); ?>
  <!--has title and url, and id, menu_item_parent-->

  <?php
  // Find path from current 'page' up to root of menu
  // Count depth, (use two max)

  echo "<ul class=\"child-menu group\">";

  $items = h7lc_first_menus_items();

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
      //echo "  <a class=\"".$a_childless_class."\" href=\"".$url."\">".$title."</a>";

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
<!-- Re-entering vanilla 3.7.25 hueman theme -->

			<?php if ( hu_get_option( 'post-nav' ) == 's1') { get_template_part('parts/post-nav'); } ?>

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
