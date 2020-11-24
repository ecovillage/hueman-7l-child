<?php

/** Similar to legacy h7lc_shortcode_upcoming_events, collect upcoming events
 * and return html for it. */
function h7lc_sd_show_upcoming() {
  global $upcoming_dates;

  $limit = 15;

  // custom query to retrieve upcoming event dates.
  $upcoming_dates = new WP_Query( array(
    'post_type'         => 'sd_cpt_date',
    'post_status'       => 'publish',
    'posts_per_page'    => $limit,
    'meta_key'          => 'sd_date_begin',
    'orderby'           => 'meta_value_num',
    'order'             => 'ASC',
    'meta_query'        => array(
      // by label would be interesting
      //array(
      //  'key'       => 'wp_event_id',
      //  'value'     => $wp_event_ids,
      //),
      array(
        'key'       => 'sd_date_begin',
        'value'     => $timestamp_today*1000, //in ms
        'type'      => 'numeric',
        'compare'   => '>=',
      ),
    )
  ));

  ob_start();

  get_template_part( 'parts/sd_event_list_alx' );

  $ret = ob_get_contents();
  ob_end_clean();

  return $ret;
}
add_shortcode( 'h7lc_sd_upcoming_dates',
  'h7lc_sd_show_upcoming' );
