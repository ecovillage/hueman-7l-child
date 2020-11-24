<?php
  use Inc\Utils\TemplateUtils as Utils;

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
      $start_month = date_i18n( __('F', 'hueman-7l-child'), $post->sd_date_begin / 1000);
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
            <?php
              $event_post = get_post( $post->wp_event_id );
              $event_url  = esc_url( get_permalink( $post->wp_event_id ) );
            ?>
            <a href="<?php echo $event_url; ?>" title="<?php echo get_the_title( $post->ID ); ?>">
              <?php Utils::get_img_remote( Utils::get_value_by_language( $event_post->sd_data['teaserPictureUrl'] ?? null ), '300', '', $alt = "remote image load failed", '<p>', '</p>', true ); ?>
            </a>
          </div>
          <div class="post-item-inner group">
            <p class="post-item-category"><a href="<?php echo $event_url; ?>" rel="category tag"><?php echo __( 'Veranstaltung', 'hueman-7l-child' ); ?></a>
            </p>
            <p class="post-item-title">
              <a href="<?php echo $event_url; ?>" rel="bookmark" title="<?php echo get_the_title( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
            </p>
            <p class="post-item-date">
              <?php echo date_i18n(__('D, d.m', 'hueman-7l-child'), $post->sd_date_begin / 1000); ?>
              -
              <?php echo date_i18n(__('D, d.m.Y', 'hueman-7l-child'), $post->sd_date_end / 1000); ?>
            </p>
          </div><!-- post-item-inner group -->
        </li>
     <?php
    } // while $upcoming_dates->have_posts()
    echo '</ul>';

    wp_reset_postdata();
  } // if $upcoming_dates->have_posts()

?>
