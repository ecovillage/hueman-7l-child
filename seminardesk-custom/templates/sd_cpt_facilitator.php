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

<section class="content">

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
  <div class="page-title pad group">
    <h2><?php echo __('Referent*', 'hueman-7l-child'); ?> - <?php the_title(); ?></h2>
  </div><!--/.page-title-->
<?php /* continue vanilla hueman archive.php v3.2.9 */ ?>


<div class="pad group entry" id="site-content" role="main">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <header class="entry-header">
                <div class="entry-header-inner section-inner medium">
                    <?php 
                    the_title( '<h1 class="archive-title">', '</h1>' );
                    ?>
                </div>
            </header>
            <div class="post-meta-wrapper post-meta-single post-meta-single-top sd-facilitator-post">
                <?php
                echo !empty( $post->sd_data['pictureUrl'] ) ? '<p>' . Utils::get_img_remote(wp_strip_all_tags($post->sd_data['pictureUrl']), '100') . '</p>' : null;
                $about = Utils::get_value_by_language( $post->sd_data['about'] );
                echo !empty($about) ? '<p>' . $about . '</p>' : null;     


                /**
                 * list upcoming event dates with this facilitator using custom query 
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

                echo '<h3>';
                echo __('Upcoming Event Dates with ', 'hueman-7l-child');
                echo get_the_title();
                echo ': </h3>';
                if ( $query_upcoming->have_posts() && !empty( $wp_event_ids ) ){
                    while ( $query_upcoming->have_posts() ) {
                        $query_upcoming->the_post();
                        $post_event = get_post( $post->wp_event_id );
                        ?>
                        <div class="event-block" style="margin:1em">
                          <div class="facilitator-event-tease-img">
                            <?php 
                              Utils::get_img_remote( wp_strip_all_tags(Utils::get_value_by_language( $post_event->sd_data['teaserPictureUrl'] ?? null )), '300', '', $alt = "remote image load failed", '', '', true );
                            ?>
                          </div>
                          <div class="facilitator-event-tease">
                            <h3>
                              <a href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>" class="event-title-link">
                                <?php
                                  Utils::get_value_by_language( $post_event->sd_data['title'], 'DE', '', '', true); 
                                ?>
                              </a>
                            </h3>
                            <div class="event-block-date">
                              <?php
                                Utils::get_date_span( $post->sd_data['beginDate'], $post->sd_data['endDate'], '', '', '', '', true);
                              ?>
                            </div>
                          </div>
                          <div class="facilitator-event-tease-text">
                            <?php
                              echo wp_strip_all_tags( Utils::get_value_by_language( $post_event->sd_data['teaser'], 'DE',  '<p>', '</p>', false ) );
                            ?>
                          </div>
                          <hr style="width:60%;margin:0em auto 2em">
                        </div>
                        <?php
                    }
                } else {
                    echo __('<p>Sorry. No upcoming event dates with this facilitator</p>', 'hueman-7l-child');
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
                echo '</p><h3>';
                echo __('Past Event Dates with ', 'hueman-7l-child');
                echo get_the_title() . ': </h3>';
                if ( $query_past->have_posts() && !empty( $wp_event_ids ) ){
                    while ( $query_past->have_posts() ) {
                        $query_past->the_post();
                        $post_event = get_post( $post->wp_event_id );
                        ?>
                            <div style="margin:1em">
                                <?php 
                                Utils::get_date_span( $post->sd_data['beginDate'], $post->sd_data['endDate'], '','','', ': ', true);
                                ?>
                                <a href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>">
                                    <?php
                                    Utils::get_value_by_language( $post_event->sd_data['title'], 'DE', '', '', true ); 
                                    ?>
                                </a>
                            </div>
                        <?php
                    }
                } else {
                    echo __('<p>Sorry, no past event dates with this facilitator</p>', 'hueman-7l-child');
                } 
                wp_reset_query();
                ?>
            </div>
            <?php
            
        }
    } else {
        ?>
        <div class="entry-header-inner section-inner small has-text-align-center">
            <h5><strong><?php echo __('Sorry, facilitator does not exist.', 'hueman-7l-child'); ?></strong></h5>
            <br>
        </div>
        <?php
    }
    ?>

</div><!-- #site-content -->

<!-- End of SeminarDesk original template -->

</section>

<?php
get_sidebar();

get_footer();
