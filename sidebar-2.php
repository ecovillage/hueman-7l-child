<div class="sidebar s2">

	<a class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fa icon-sidebar-toggle"></i></a>

	<div class="sidebar-content">
		<?php if ( hu_is_checked('sidebar-top') ): ?>
  		<div class="sidebar-top group">
  			<p><?php _e('More','hueman'); ?></p>
  		</div>
    <?php endif; ?>

<!-- Leaving vanilla 3.3.4 hueman theme -->
    <? if(false) { ?>
    <?php
      $posts = new WP_Query( array(
        'post_type'  => array( 'ev7l-event' ),
        'showposts'  => 5,
        'ignore_sticky_posts' => true,
        //'orderby'    => $instance['posts_orderby'],
        //'order'      => 'dsc',
        //'date_query' => array(
        //  array(
        //    'after' => $instance['posts_time'],
        //  ),
        //),
      ) );
      // before widget ...
    ?>

    <div id="event-sidebar-list" class="widget widget_hu_posts">
      <ul class="alx-posts group thumbs-enabled">
        <?php while ($posts->have_posts()): $posts->the_post(); ?>
        <li>

          <div class="post-item-thumbnail">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
              <?php hu_the_post_thumbnail('thumb-medium'); ?>
              <?php if ( has_post_format('video') && !is_sticky() ) echo'<span class="thumb-icon small"><i class="fa fa-play"></i></span>'; ?>
              <?php if ( has_post_format('audio') && !is_sticky() ) echo'<span class="thumb-icon small"><i class="fa fa-volume-up"></i></span>'; ?>
              <?php if ( is_sticky() ) echo'<span class="thumb-icon small"><i class="fa fa-star"></i></span>'; ?>
            </a>
          </div>

          <div class="post-item-inner group">
            <?php if(false && $instance['posts_category']) { ?><p class="post-item-category"><?php the_category(' / '); ?></p><?php } ?>
            <p class="post-item-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></p>
            <?php if(false && $instance['posts_date']) { ?><p class="post-item-date"><?php the_time('j M, Y'); ?></p><?php } ?>
          </div>

        </li>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      </ul><!--/.alx-posts-->
      <?php // after widget ... ?>
    </div> <!-- #event-sidebar-list -->
    <? } ?>
<!-- Re-entering vanilla 3.3.4 hueman theme -->

		<?php if ( hu_get_option( 'post-nav' ) == 's2') { get_template_part('parts/post-nav'); } ?>

		<?php hu_print_widgets_in_location('s2') ?>

	</div><!--/.sidebar-content-->

</div><!--/.sidebar-->
