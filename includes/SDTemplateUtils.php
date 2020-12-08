<?php
//namespace includes;

use Inc\Utils\TemplateUtils as Utils;

class SDTemplateUtils {

  public static function get_upcoming_dates_query( $event_id ) {
    $custom_query = new WP_Query(
      array(
        'post_type'     => 'sd_cpt_date',
        'post_status'   => 'publish',
        'meta_key'      => 'sd_date_begin',
        'orderby'       => 'meta_value_num',
        'order'         => 'ASC',
        'limit'         => 1,
        'meta_query'    => array(
          'relation'      => 'AND',
          'condition1'    => array(
            'key'           => 'sd_date_begin',
            'value'         => wp_date('Y-m-d')*1000, //in ms
            'type'          => 'NUMERIC',
            'compare'       => '>=',
          ),
          'condition2'    => array(
            'key'           => 'sd_event_id',
            'value'         => $event_id,
            'type'          => 'CHAR',
            'compare'       => '='
          ),
        ),
      )
    );

    return $custom_query;
  }

  public static function get_first_upcoming_date( $event_id ) {
    $custom_query = self::get_upcoming_dates_query( $event_id );

    $date_posts = $custom_query->get_posts();

    if ( $custom_query->have_posts() ) {
      return $date_posts[0];
    }

    return NULL;
  }

  public static function date_props_html( $date ) {
    if ( ! $date ) {
      return NULL;
    }

    $date_str = Utils::get_date( $date->sd_date_begin,  $date->sd_date_end );
    // rtrim() or wp_strip_all_tags...
    $title_str = wp_strip_all_tags( $date->post_title ) . ': ';
    $price_str = wp_strip_all_tags( Utils::get_value_by_language( $date->sd_data['priceInfo'] ) );

    $venue_props = $date->sd_data['venue'];
    $venue = Utils::get_venue( $venue_props );

    $title = wp_strip_all_tags( $date->post_title ) . ': ';
    $date_props = array();
    array_push( $date_props, $date_str, $price_str, $venue );
    $date_props = array_filter( $date_props ); // remove all empty values from array
    $date_html = $title . implode( ', ', $date_props );

    $status = $date->sd_data['status'];

    if ( $status == 'fully_booked' || $status == 'wait_list') {
      $status_translated = array(
        'available'     => __( 'Booking Available', 'hueman-7l-child' ),
        'fully_booked'  => __( 'Fully Booked', 'hueman-7l-child' ),
        'limited'       => __( 'Limited Booking', 'hueman-7l-child' ),
        'wait_list'     => __( 'Waiting List', 'hueman-7l-child' ),
      );
      $date_html = $date_html . '<span class="event-status-'. $status . '">' . $status_translated[$status] . "</span>";
    }

    return $date_html;
  }

  public static function get_date_count( $event_id ) {
    $custom_query = self::get_upcoming_dates_query( $event_id );

    return $custom_query->post_count;
  }

  public static function get_dates_str( $date ) {
    if ( $date ) {
      return Utils::get_date( $date->sd_date_begin, $date->sd_date_end );
    }
    else {
      return NULL;
    }
  }

  public static function get_facilitator_post( $sd_facilitator_id ) {
    $custom_query = new WP_Query(
        array(
            'post_type'   => 'sd_cpt_facilitator',
            'post_status' => 'publish',
            'meta_key'    => 'sd_facilitator_id',
            'meta_query'  => array(
                'key'     => 'sd_facilitator_id',
                'value'   => $sd_facilitator_id,
                'type'    => 'numeric',
                'compare' => '=',
            ),
        )
    );
    $facilitator_posts = $custom_query->get_posts();

    if ( $custom_query->have_posts() ) {
      return $facilitator_posts[0];
    }

    return NULL;
  }

}

?>
