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
 * The template for taxonomy sd_txn_dates with upcoming event dates
 * 
 * @package SeminardeskPlugin
 */

use Inc\Utils\TemplateUtils as Utils;

get_header();
?>
<main id="site-content" role="main">
    
    <header class="archive-header has-text-align-center header-footer-group">
        <div class="archive-header-inner section-inner medium">
                <h1 class="archive-title">Upcoming Event Dates</h1>
        </div><!-- .archive-header-inner -->
    </header><!-- .archive-header -->
    
    <?php
    $term_set = '';
	if ( have_posts() ) {
		while ( have_posts() ) {
            the_post();
            $post_event = get_post( $post->wp_event_id );
            $post_event_status = $post_event->post_status;
            ?>
            <div class="entry-header-inner section-inner small">
                <?php
                $term = get_the_terms( $post, 'sd_txn_dates' );
                if ($term['0']->description != $term_set ){
                    $term_set = $term['0']->description;
                    echo '<h4>' . $term_set . '</h4>';
                }
                if ( $post_event_status === 'publish' ){
                    ?>
                    <a href="<?php echo get_permalink($post_event); ?>">
                    <?php 
                    Utils::get_value_by_language( $post_event->sd_data['title'], 'DE', '<h4>', '</h4>', true); 
                    ?>
                </a>
                <?php
                } else {
                    Utils::get_value_by_language( $post_event->sd_data['title'], 'DE', '<h4>', '</h4>', true);
                }
                Utils::get_date( $post->sd_data['beginDate'], $post->sd_data['endDate'], '<p><strong>Date: </strong>', '</p>', true);
                Utils::get_facilitators( $post_event->sd_data['facilitators'], '<p><strong>Facilitator: </strong>', '</p>', true );
                echo Utils::get_value_by_language( $post->sd_data['priceInfo'], 'DE', '<p><strong>Price: </strong>', '</p>' );
                Utils::get_venue( $post->sd_data['venue'], '<p><strong>Venue: </strong>', '</p>', true);
                Utils::get_img_remote( Utils::get_value_by_language( $post_event->sd_data['teaserPictureUrl'] ?? null ), '300', '', $alt = "remote image load failed", '<p>', '</p>', true );
                Utils::get_value_by_language( $post_event->sd_data['teaser'], 'DE',  '<p>', '</p>', true );
                if ( $post_event_status === 'publish' ){
                    ?>
                    <a href="<?php echo get_permalink($post->wp_event_id); ?>">
                        More ...
                    </a>
                    <?php
                } 
                ?>
            </div>
            <?php 
        }?>
        <div class="has-text-align-center">
            <br><p>
                <?php
                echo paginate_links( array(
                    'base' => add_query_arg('page', '%#%'),
                    'format' => '?page=%#%',
                ) );
                ?>
            </p>
        </div>
        <?php
	} else {
        ?>
        <div class="entry-header-inner section-inner small has-text-align-center">
            <h5>
                <strong>Sorry, no upcoming event dates available.</strong>
            </h5>
            <br>
        </div>
        <?php

    }
    wp_reset_query();
	?>

</main><!-- #site-content -->

<?php
get_footer();


