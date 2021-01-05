<?php

/**
 * Modifies search query
 * 
 * @param mixed $search 
 * @return mixed 
 */
function modify_search( $search ) {
    if ( class_exists( 'Polylang' ) && defined( 'SD_CPT' ) && is_search() && is_main_query() ) {
        global $wp_query;
        $post_types = array_keys( SD_CPT );
        $custom_posts = get_posts( array(
            'post_type'         => $post_types,
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            's'                 => $search,
            'tax_query'         => '',
            'lang'              => '',
        ) );
        $wp_query->posts = array_unique( array_merge( $wp_query->posts, $custom_posts ), SORT_REGULAR );
        $wp_query->post_count = count( $wp_query->posts );
        $wp_query->found_posts = count( $wp_query->posts );
    }

    return $search;
}

add_filter( 'get_search_query', 'modify_search' );
