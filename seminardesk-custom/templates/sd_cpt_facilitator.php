<?php
/*
 * File originally from
 * https://bitbucket.org/seminardesk/seminardesk-wordpress/src/develop/templates/
 * Original content Copyright 2020 SeminarDesk – Danker, Smaluhn & Tammen GbR .
 * Modifications Copyright 2020 Freundeskreis Ökodorf e.V.
 */
?>

<?php
/**
 * The template for single post of CPT sd_cpt_facilitator
 * 
 * @package SeminardeskPlugin
 */

use Inc\Utils\TemplateUtils as Utils;

$timestamp_today = strtotime(wp_date('Y-m-d')); // current time
// $timestamp_today = strtotime('2020-09-01'); // debugging

get_header();
?>
<main id="site-content" role="main">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <header class="entry-header has-text-align-center">
                <div class="entry-header-inner section-inner medium">
                    <?php 
                    the_title( '<h1 class="archive-title">', '</h1>' );
                    ?>
                </div>
            </header>
            <div class="post-meta-wrapper post-meta-single post-meta-single-top">
                <?php
                echo !empty( $post->sd_data['pictureUrl'] ) ? '<p>' . Utils::get_img_remote($post->sd_data['pictureUrl'], '100') . '</p>' : null;
                $about = Utils::get_value_by_language( $post->sd_data['about'] );
                echo !empty($about) ? '<p>' . $about . '</p>' : null;     

                /**
                 * Example to list events with upcoming event dates with this facilitator using custom queries
                 */

                // custom query to retrieve all events with this facilitator
                $query_events = new WP_Query( array(
                    'post_type'         => 'sd_cpt_event',
                    'post_status'       => 'publish',
                    'posts_per_page'    => '-1',
                    'tax_query'         => array( 
                        array(
                            'taxonomy'          => 'sd_txn_facilitators',
                            'field'             => 'name',
                            'terms'             => $post->sd_facilitator_id,
                            'include_children'  => false,
                        )
                    ),
                ) );
                // loop through events with this facilitator
                echo '<strong><p style="margin:1em 0em 1em">Events with ' . get_the_title() . ': </p></strong>';
                if ( $query_events->have_posts() ) {
                    while ( $query_events->have_posts() ) {
                        $query_events->the_post();
                        ?>
                        <div style="margin:1em" >
                            <?php
                            $url = Utils::get_value_by_language($post->sd_data['headerPictureUrl']) ?? null;
                            echo Utils::get_img_remote( $url, '300', '', 'remote image failed' );
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>">
                                <?php
                                Utils::get_value_by_language( $post->sd_data['title'], 'DE', '', '<br>', true); 
                                ?>
                            </a>
                            <?php
                            echo Utils::get_value_by_language($post->sd_data['subtitle']);
                            // custom query to retrieve all upcoming event dates for this event 
                            $wp_event_id = $post->ID;
                            $query_date_up = new WP_Query( array(
                                'post_type'         => 'sd_cpt_date',
                                'post_status'       => 'publish',
                                'posts_per_page'    => '-1',
                                'meta_key'          => 'sd_date_begin',
                                'orderby'           => 'meta_value_num',
                                'order'             => 'ASC',
                                'meta_query'       => array(
                                    array(
                                        'key'       => 'wp_event_id',
                                        'value'     => $wp_event_id,
                                    ),
                                    array(
                                        'key'       => 'sd_date_begin',
                                        'value'     => $timestamp_today*1000, //in ms
                                        'type'      => 'numeric',
                                        'compare'   => '>=',
                                    ),
                                )
                            ) );
                            ?>
                            <div style="margin:0.5em;">
                                <?php
                                // loop through upcoming event dates for this event
                                if ( $query_date_up->have_posts() ) {
                                    echo 'Upcoming Event Dates for this Event:<br>';
                                        while ( $query_date_up->have_posts() ) {
                                            $query_date_up->the_post();
                                            Utils::get_date( $post->sd_data['beginDate'], $post->sd_data['endDate'], '', '<br>', true);
                                        }
                                        ?>
                                    <?php
                                }else{
                                    echo 'Sorry. No upcoming event dates for this event :(';
                                }
                            ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>Sorry. At the moment this facilitator has no events registered with us</p>';
                }
                wp_reset_query();

                /**
                 * example to list upcoming event dates with this facilitator using custom query 
                 */

                // retrieve all event ids with this facilitator 
                $wp_event_ids = get_posts( array(
                    'post_type'         => 'sd_cpt_event',
                    'post_status'       => 'publish',
                    'posts_per_page'    => '-1',
                    'fields'            => 'ids', // return ids instead of post objects
                    'tax_query'         => array( 
                        array(
                            'taxonomy'          => 'sd_txn_facilitators',
                            'field'             => 'name',
                            'terms'             => $post->sd_facilitator_id,
                            'include_children'  => false,
                        )
                    ),
                ) );
                // custom query to retrieve upcoming event dates with this facilitator (by using event ids)
                $query_upcoming = new WP_Query( array(
                    'post_type'         => 'sd_cpt_date',
                    'post_status'       => 'publish',
                    'posts_per_page'    => '-1',
                    'meta_key'          => 'sd_date_begin',
                    'orderby'           => 'meta_value_num',
                    'order'             => 'ASC',
                    'meta_query'        => array(
                        array(
                            'key'       => 'wp_event_id',
                            'value'     => $wp_event_ids,
                        ),
                        array(
                            'key'       => 'sd_date_begin',
                            'value'     => $timestamp_today*1000, //in ms
                            'type'      => 'numeric',
                            'compare'   => '>=',
                        ),
                    )
                ));
                // loop through upcoming event dates with this facilitator
                echo '<strong><p style="margin:1em 0em 1em">Upcoming Event Dates with ' . get_the_title() . ': </p></strong>';
                if ( $query_upcoming->have_posts() && !empty( $wp_event_ids ) ){
                    while ( $query_upcoming->have_posts() ) {
                        $query_upcoming->the_post();
                        $post_event = get_post( $post->wp_event_id );
                        ?>
                        <div style="margin:1em">
                            <?php 
                            Utils::get_img_remote( Utils::get_value_by_language( $post_event->sd_data['teaserPictureUrl'] ?? null ), '300', '', $alt = "remote image load failed", '<p>', '</p>', true );
                            Utils::get_date( $post->sd_data['beginDate'], $post->sd_data['endDate'], '', ': ', true);
                            ?>
                            <a href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>">
                                <?php
                                Utils::get_value_by_language( $post_event->sd_data['title'], 'DE', '', '', true); 
                                ?>
                            </a>
                            <p>
                                <?php
                                echo wp_strip_all_tags( Utils::get_value_by_language( $post_event->sd_data['teaser'], 'DE',  '<p>', '</p>', false ) );
                                ?>
                            </p>
                            <hr style="width:60%;margin:0em auto 2em">
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>Sorry. No upcoming event dates with this facilitator</p>';
                }        
                wp_reset_query();

                // custom query to retrieve past event dates with this facilitator (by using event ids)
                $query_past = new WP_Query( array(
                    'post_type'         => 'sd_cpt_date',
                    'post_status'       => 'publish',
                    'posts_per_page'    => '-1',
                    'meta_key'          => 'sd_date_begin',
                    'orderby'           => 'meta_value_num',
                    'order'             => 'DESC',
                    'meta_query'        => array(
                        array(
                            'key'       => 'wp_event_id',
                            'value'     => $wp_event_ids,
                        ),
                        array(
                            'key'       => 'sd_date_begin',
                            'value'     => $timestamp_today*1000, //in ms
                            'type'      => 'numeric',
                            'compare'   => '<',
                        ),
                    )
                ));
                // loop through past event dates with this facilitator
                echo '</p><strong><p style="margin:1em 0em 1em">Past Event Dates with ' . get_the_title() . ': </p></strong>';
                if ( $query_past->have_posts() && !empty( $wp_event_ids ) ){
                    while ( $query_past->have_posts() ) {
                        $query_past->the_post();
                        $post_event = get_post( $post->wp_event_id );
                        ?>
                            <div style="margin:1em">
                                <?php 
                                Utils::get_date( $post->sd_data['beginDate'], $post->sd_data['endDate'], '', ': ', true);
                                ?>
                                <a href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>">
                                    <?php
                                    Utils::get_value_by_language(     $post_event->sd_data['title'], 'DE', '', '', true); 
                                    ?>
                                </a>
                            </div>
                        <?php
                    }
                } else {
                    echo '<p>Sorry, no past event dates with this facilitator</p>';
                } 
                wp_reset_query();
                ?>
            </div>
            <?php
            
        }
    } else {
        ?>
        <div class="entry-header-inner section-inner small has-text-align-center">
            <h5><strong>Sorry, facilitator does not exist.</strong></h5>
            <br>
        </div>
        <?php
    }
    ?>

</main><!-- #site-content -->

<?php
get_footer();