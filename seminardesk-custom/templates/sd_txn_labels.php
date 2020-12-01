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
 * The template for taxonomy sd_txn_labels by term labelGroup or label.
 * 
 * @package SeminardeskPlugin
 */

use Inc\Utils\TemplateUtils as Utils;

$timestamp_today = strtotime(wp_date('Y-m-d')); // current time
// $timestamp_today = strtotime('2020-07-01'); // debugging
$txn = get_taxonomy(get_query_var( 'taxonomy' ));
$term = get_queried_object();
$term_meta = get_term_meta( $term->term_id, 'sd_data' )[0] ?? null;

$title = $term->description . ' (' . $term_meta['abbreviation'] . ')';

get_header();

?>

<section class="content">

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
  <?php /*hu_get_template_part('parts/page-title');*/ ?>
  <div class="page-title pad group">
  <h2><?php echo $title; ?></h2>
  </div><!--/.page-title-->
<?php /* Re-entering vanilla hueman */ ?>

<!-- SeminarDesk original template -->

<div class="pad group entry" id="site-content" role="main">

    <header class="archive-header has-text-align-center header-footer-group">
        <div class="archive-header-inner section-inner medium">
            <h1 class="archive-title">
                <?php 
                $title = $term->description . ' (' . $term_meta['abbreviation'] . ')';
                echo $title;
                ?>
            </h1>
            <?php 
            $img_url = Utils::get_value_by_language($term_meta['pictureUrl']) ?? null;
            echo '<p>' . Utils::get_value_by_language($term_meta['description']) . '</p>';
            echo Utils::get_img_remote( $img_url, '150', '', 'remote image failed', '', '' );        
            ?>
        </div><!-- .archive-header-inner -->
    </header><!-- .archive-header -->

    <?php
    
    $post_type = get_post_type();
    switch ($post_type){
        /**
         * template part - sd_cpt_date
         */ 
        case 'sd_cpt_date':
            ?>
            <div class="entry-header-inner section-inner small">
                <p></p>
                <p>
                    <strong>Upcoming event dates: </strong>
                    <?php
                    // modify query for upcoming event dates
                    set_query_var( 'meta_key', 'sd_date_begin' );
                    set_query_var( 'orderby', 'meta_value_num' );
                    set_query_var( 'order', 'ASC' );
                    $meta_query = array(
                        array(
                            'key'       => 'sd_date_begin',
                            'value'     => $timestamp_today*1000, //in ms
                            'type'      => 'numeric',
                            'compare'   => '>=',
                        ),
                    );
                    $wp_query->meta_query->queries = $meta_query;
                    set_query_var( 'meta_query', $wp_query->meta_query->queries);
                    $wp_query->get_posts();
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            $sd_data = $post->sd_data;
                            $post_event = get_post( $post->wp_event_id );
                            $post_event_status = $post_event->post_status;
                            ?>
                            <p>
                                <?php 
                                if ( $post_event_status === 'publish' ){
                                    ?>
                                    <a href="<?php echo esc_url(get_permalink($post_event)); ?>">
                                        <?php 
                                        the_title( '<p><h2 class="archive-title">', '</h2></p>' );
                                        ?>
                                    </a>
                                    <?php
                                } else {
                                    the_title( '<p><h2 class="archive-title">', '</h2></p>' );
                                }
                                Utils::get_date( $post->sd_date_begin, $post->sd_date_end, '<div class="sd-event-date"><strong>Date: </strong>', '</div>', true);
                                Utils::get_facilitators($post_event->sd_data['facilitators'], '<div class="sd-event-facilitators"><strong>Facilitator: </strong>', '</div>', true);
                                Utils::get_value_by_language($post->sd_data['priceInfo'], 'DE', '<div class="sd-event-price"><strong>Price: </strong>', '</div>', true );
                                Utils::get_venue($post->sd_data['venue'], '<div class="sd-event-venue"><strong>Venue: </strong>', '</div>', true);
                                Utils::get_img_remote(  Utils::get_value_by_language($post_event->sd_data['teaserPictureUrl'] ?? null), '300', '', 'remote image failed', '', '', true);
                                echo Utils::get_value_by_language($post_event->sd_data['teaser']);
                                if ( $post_event_status === 'publish' ){
                                    ?>
                                    <div class="sd-event-more-link">
                                        <a class="button" href="<?php echo esc_url(get_permalink($post_event)); ?>">More</a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </p>
                            <?php
                        }
                    } else {
                        echo 'Sorry, no upcoming event dates';
                    }
                    ?>
                </p>
                <p>
                    <strong>Past event dates: </strong>
                    <?php
                    // modify query for past event dates
                    set_query_var( 'meta_key', 'sd_date_begin' );
                    set_query_var( 'orderby', 'meta_value_num' );
                    set_query_var( 'order', 'DSC' );
                    $meta_query = array(
                        array(
                            'key'       => 'sd_date_begin',
                            'value'     => $timestamp_today*1000, //in ms
                            'type'      => 'numeric',
                            'compare'   => '<',
                        ),
                    );
                    $wp_query->meta_query->queries = $meta_query;
                    set_query_var( 'meta_query', $wp_query->meta_query->queries );
                    $wp_query->get_posts();
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            $sd_data = $post->sd_data;
                            $post_event = get_post( $post->wp_event_id );
                            if ( $post_event_status === 'publish' ){
                                ?>
                                <a href="<?php echo esc_url(get_permalink($post_event)); ?>">
                                    <?php 
                                    the_title( '<p><h2 class="archive-title">', '</h2></p>' );
                                    ?>
                                </a>
                                <?php
                            } else {
                                the_title( '<p><h2 class="archive-title">', '</h2></p>' );
                            }
                            Utils::get_date( $post->sd_date_begin, $post->sd_date_end, '<div class="sd-event-date"><strong>Date: </strong>', '</div>', true);
                            Utils::get_facilitators($post_event->sd_data['facilitators'], '<div class="sd-event-facilitators"><strong>Facilitator: </strong>', '</div>', true);
                            Utils::get_value_by_language($post->sd_data['priceInfo'], 'DE', '<div class="sd-event-price"><strong>Price: </strong>', '</div>', true );
                            Utils::get_venue($post->sd_data['venue'], '<div class="sd-event-venue"><strong>Venue: </strong>', '</div>', true);
                            Utils::get_img_remote(  Utils::get_value_by_language($post_event->sd_data['teaserPictureUrl'] ?? null), '300', '', 'remote image failed', '', '', true);
                            echo Utils::get_value_by_language($post_event->sd_data['teaser']); 
                            if ( $post_event_status === 'publish' ){
                                ?>
                                <div class="sd-event-more-link">
                                    <a class="button" href="<?php echo esc_url(get_permalink($post_event)); ?>">More</a>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        echo 'Sorry, no past event dates';
                    }
                    ?>
                </p>
            </div>
            <?php
            break;
        /**
         * template part - sd_cpt_facilitator
         */
        case 'sd_cpt_facilitator':
            global $wp_query;
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    $sd_data = $post->sd_data;
                    ?>
                    <div class="entry-header-inner section-inner small">
                        <a href="<?php echo esc_url(get_permalink()); ?>">
                            <?php 
                            the_title( '<p><h1 class="archive-title">', '</h1></p>' );
                            ?>
                        </a>
                        <?php
                        echo !empty( $post->sd_data['pictureUrl'] ) ? '<p>' . Utils::get_img_remote($post->sd_data['pictureUrl'], '100') . '</p>' : null;
                        $about = Utils::get_value_by_language( $post->sd_data['about'] );
                        echo !empty($about) ? '<p>' . $about . '</p>' : null;     
                        ?>
                    </div>
                    <?php
                }
                ?>
                <div class="has-text-align-center">
                    <br><p>
                        <?php
                        echo paginate_links();
                        ?>
                    </p>
                </div>
                <?php
            } else {
                ?>
                <div class="entry-header-inner section-inner small has-text-align-center">
                    <h5><strong>Sorry, does not exist.</strong></h5>
                    <br>
                </div>
                <?php
            }
            break;
        /**
         * template part - sd_cpt_label (labelGroup/label), sd_cpt_events
         */
        default:
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    $sd_data = $post->sd_data;
                    ?>
                    <div class="sd-event">
                    <div class="entry-header-inner section-inner small">
                      <div class="sd-event-title">
                        <a href="<?php echo esc_url(get_permalink()); ?>">
                            <?php 
                            the_title( '<h1 class="archive-title">', '</h1>' );
                            ?>
                        </a>
                      </div>
                      <div class="sd-event-container">
                        <div class="sd-event-props">
                          <?php
                          // TODO: get the date
                          echo '<p>' . Utils::get_value_by_language($sd_data['subtitle'] ?? null ) . '</p>'; 
                          Utils::get_facilitators( $sd_data['facilitators'], '<div class="sd-event-facilitators"><strong>' . __('Facilitator: ', 'hueman-7l-child') . '</strong>', '</div>', true);
                          ?>
                        </div> <!-- sd-event-props -->
                        <div class="sd-event-image">
                          <?php
                          $img_url = Utils::get_value_by_language($sd_data['headerPictureUrl'] ?? $sd_data['pictureUrl'] ?? null);
                          echo Utils::get_img_remote( $img_url, '300', '', 'remote image failed', '<p>', '</p>' );
                          ?>
                        </div> <!-- sd-event-image -->
                        <div class=sd-event-teaser>
                          <?php
                          // TODO: replace by teaser
                          echo '<div>' . Utils::get_value_by_language( $sd_data['teaser'] ?? $sd_data['description'] ?? null ) . '</div>';
                          ?>
                        </div> <!-- sd-event-teaser -->
                      </div> <!-- sd-event-container -->
                    </div>
                    </div> <!-- sd-event -->
                    <?php  
                }   
            } else {
                ?>
                <div class="has-text-align-center">
            <br><p>
                <?php
                        echo paginate_links();
                ?>
            </p>
        </div>
                <?php
            }
    } 
?>

</div><!-- #site-content -->

<!-- End of SeminarDesk original template -->

</section>

<?php

get_sidebar();

get_footer();
?>
