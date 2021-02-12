<?php

/** Similar to legacy h7lc_shortcode_upcoming_events, collect upcoming events
 * and return html for it. */
function h7lc_sd_show_upcoming() {
  global $upcoming_dates;

  $limit = 15;
  $timestamp_today = strtotime( wp_date('Y-m-d') ); // current time

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

function h7lc_sd_show_registration_button( $atts ) {
  $a = shortcode_atts( array('event_uuid' => 'uid'), $atts );
  $uuid = $a['event_uuid'];
  if ( !$uuid ) {
    return '';
  }

  require_once( get_stylesheet_directory() . '/includes/SDTemplateUtils.php' );

  ob_start();
  $event_post  = SDTemplateUtils::get_event_by_sd_event_id( $uuid );
  $booking_url = esc_url( $event_post->sd_data['bookingPageUrl'][0]['value'] ?? null );

  echo '<style>';
  get_template_part( 'seminardesk-custom/templates/assets/sd_cpt_event.css' );
  echo '</style>';
 
  set_query_var( 'booking_url', $booking_url );
  get_template_part( 'parts/booking_modal' );

  echo '<script>';
  get_template_part( 'seminardesk-custom/templates/assets/sd_cpt_event.js' );
  echo '</script>';

  echo '<br><p><button class="sd-modal-booking-btn">Buchung</button></p>';

  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_shortcode( 'sd_booking_button', 'h7lc_sd_show_registration_button' );
