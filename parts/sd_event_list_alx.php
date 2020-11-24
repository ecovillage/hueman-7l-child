<?php
  global $upcoming_dates;

  if ( $upcoming_dates->have_posts() ) {
    // move separate uls into loop

    echo '<ul class="ev7l_event_list alx-posts group thumbs-enabled" style="margin: 0;">';

    $month = -1;
    $year = -1;
    global $post; // TODO iterate over result without polluting global post

    while ( $upcoming_dates->have_posts() ) {
      $upcoming_dates->the_post();

      // New month?
      $start_month = date_i18n( __('F', 'hueman-7l-child'), $post->sd_date_begin );
      // wp_date( 'D. d.m.Y', $only_date->sd_date_begin/1000);
      if ( $month != $start_month ) {
        $month = $start_month;
        echo '</ul>';
        echo '<h2 class="event-list-month-name">' . $month . '</h2>';
        echo '<ul class="ev7l_event_list alx-posts group thumbs-enabled" style="margin: 0;">';
      }
      ?>
        <li>
          <div class="post-item-thumbnail">
            <a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php echo get_the_title( $post->ID ); ?>">
            <?php hu_the_post_thumbnail( 'thumb-medium' ); ?>
            </a>
          </div>
          <div class="post-item-inner group">
            <p class="post-item-category"><a href="" rel="category tag"><?php echo __( 'Veranstaltung', 'hueman-7l-child' ); ?></a>
            </p>
            <p class="post-item-title">
              <a href="<?php echo get_permalink( $post->ID ); ?>" rel="bookmark" title="<?php echo get_the_title( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
            </p>
            <p class="post-item-date">
              <?php echo date_i18n(__('D, d.m', 'hueman-7l-child'), $post->sd_date_begin); ?>
              -
              <?php echo date_i18n(__('D, d.m.Y', 'hueman-7l-child'), $post->sd_date_end); ?>
            </p>
          </div><!-- post-item-inner group -->
        </li>
     <?php
    } // while $upcoming_dates->have_posts()
    echo '</ul>';

    wp_reset_postdata();
  } // if $upcoming_dates->have_posts()

?>
