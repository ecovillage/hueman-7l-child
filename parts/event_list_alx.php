<?php
  global $upcoming_events;
  if ( $upcoming_events->have_posts() ) {
    echo '<div class="widget widget_ev7l_event_list_widget">';
    // move separate uls into loop
    echo '<ul class="ev7l_event_list alx-posts group thumbs-enabled">';
    $month = -1;
    $year = -1;
    global $post;
    while ( $upcoming_events->have_posts() ) {
      $upcoming_events->the_post();
      // New month?
      $start_month = date_i18n(__('F', 'hueman-7l-child'), get_post_meta($post->ID, 'fromdate', true));
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
            </a>
          </div>
          <div class="post-item-inner group">
            <p class="post-item-category"><a href="" rel="category tag"><?php echo __('Veranstaltung', 'hueman-7l-child'); ?></a>
            </p>
            <p class="post-item-title">
              <a href="<?php echo get_permalink($post->ID); ?>" rel="bookmark" title="<?php echo get_the_title($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a>
            </p>
            <p class="post-item-date">
              <?php echo date_i18n(__('D, d.m', 'hueman-7l-child'), get_post_meta($post->ID, 'fromdate', true)); ?>
              -
              <?php echo date_i18n(__('D, d.m.Y', 'hueman-7l-child'), get_post_meta($post->ID, 'todate', true)); ?>
            </p>
          </div>
        </li>
     <?php
    } // while $upcoming_events->have_posts()
    echo '</ul>';
    echo '</div>';
  } // if $upcoming_events->have_posts()

?>
