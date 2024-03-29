<?php
/*
 * File originally from
 * https://bitbucket.org/seminardesk/seminardesk-wordpress/src/develop/templates/
 * Original content Copyright 2020 SeminarDesk – Danker, Smaluhn & Tammen GbR .
 * Modifications Copyright 2020 Freundeskreis Ökodorf e.V.
 *
 * Accessible e.g. through /termine/upcoming
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

<section class="content">

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
  <?php /*hu_get_template_part('parts/page-title');*/ ?>
  <div class="page-title pad group">
  <h2><?php _e( 'Upcoming Event Dates', 'hueman-7l-child' ); ?></h2>
  </div><!--/.page-title-->
<?php /* Re-entering vanilla hueman */ ?>

<!-- SeminarDesk original template -->

<div class="pad group entry" id="site-content" role="main">
    
    <?php
    $term_set = '';
	if ( have_posts() ) {
		while ( have_posts() ) {
            the_post();
            $post_event = get_post( $post->wp_event_id );
            $post_event_status = $post_event->post_status;
            ?>
            <div class="sd-event">
              <div class="entry-header-inner section-inner small">
                <?php
                $term = get_the_terms( $post, 'sd_txn_dates' );
                if ( $term['0']->description != $term_set ){
                    $term_set = $term['0']->description;
                    echo '<h4>' . $term_set . '</h4>';
                }
                ?>
                <div class="sd-event-title"><?php
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
                ?>
                </div>

                <div class="sd-event-container">
                  <div class="sd-event-props">
                    <?php
                            Utils::get_date_span( $post->sd_date_begin, $post->sd_date_end, '', '', '<div class="sd-event-date">' . __('<strong>Date: </strong>', 'hueman-7l-child'), '</div>', true);
                      Utils::get_facilitators( $post_event->sd_data['facilitators'], '<div class="sd-event-facilitators"><strong>' . __('Facilitator: ', 'hueman-7l-child') . '</strong>', '</div>', true );
                      echo Utils::get_value_by_language( $post->sd_data['priceInfo'], 'DE', '<div class="sd-event-price"><strong>' . __('Price: ', 'hueman-7l-child') . '</strong>', '</div>' );
                      Utils::get_venue( $post->sd_data['venue'], '<div class="sd-event-venue"><strong>' . __('Venue: ', 'hueman-7l-child') . '</strong>', '</p>', true);
                    ?>
                  </div> <!-- sd-event-props -->

                  <div class="sd-event-image">
                    <?php
                      Utils::get_img_remote( Utils::get_value_by_language( $post_event->sd_data['teaserPictureUrl'] ?? null ), '300', '', $alt = "remote image load failed", '<p>', '</p>', true );
                    ?>
                  </div> <!-- sd-event-image -->

                  <div class="sd-event-teaser">
                    <?php
                      Utils::get_value_by_language( $post_event->sd_data['teaser'], 'DE',  '<p>', '</p>', true );
                      if ( $post_event_status === 'publish' ){
                          ?>
                          <a href="<?php echo get_permalink($post->wp_event_id); ?>">
                            <?php _e( 'More', 'hueman-7l-child' ); ?>
                          </a>
                          <?php
                      }
                    ?>
                  </div> <!-- sd-event-teaser -->
                </div> <!-- sd-event-container -->
              </div>
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
              <strong><?php _e( 'Sorry, no upcoming event dates available.', 'hueman-7l-child' ); ?></strong>
            </h5>
            <br>
        </div>
        <?php

    }
    wp_reset_query();
	?>

</div><!-- #site-content -->

<!-- End of SeminarDesk original template -->

</section>

<?php

get_sidebar();

get_footer();
?>
