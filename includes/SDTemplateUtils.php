<?php
namespace Includes;

class SDTemplateUtils {

  public static function get_first_upcoming_date( $event_id ) {
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


    $date_posts = $custom_query->get_posts();

    if ( $custom_query->have_posts() ) {
      return $date_posts[0];
    }

    return "get_first_date test";
  }
}

?>
