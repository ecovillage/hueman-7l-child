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
 * The template for taxonomy sd_txn_dates by year or month
 * 
 * @package SeminardeskPlugin
 */

use Inc\Utils\TemplateUtils as Utils;

get_header();
?>

<section class="content">

    <?php
    $txn = get_taxonomy(get_query_var( 'taxonomy' ));
    $title = ucfirst($txn->rewrite['slug']) . ': '. get_queried_object()->name;
    ?>

<?php /* Leaving vanilla hueman 3.2.9 single.php */ ?>
  <?php /*hu_get_template_part('parts/page-title');*/ ?>
  <div class="page-title pad group">
  <h2><?php echo wp_kses_post( $title ); ?></h2>
  </div><!--/.page-title-->
<?php /* Re-entering vanilla hueman */ ?>

<!-- SeminarDesk original template -->

<div class="pad group entry" id="site-content" role="main">

    <header class="archive-header has-text-align-center header-footer-group">

        <div class="archive-header-inner section-inner medium">

            <!--<?php if ( $title ) { ?>
                <h1 class="archive-title"><?php echo wp_kses_post( $title ); ?></h1>
            <?php } ?>-->

        </div><!-- .archive-header-inner -->

    </header><!-- .archive-header -->

    <?php
	if ( have_posts() ) {
		while ( have_posts() ) {
            the_post();
            $post_event = get_post( $post->wp_event_id );
            ?>
            <div class="sd-event">
                <div class="entry-header-inner section-inner small">
                    <div class="sd-event-title">
                        <a href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>">
                            <?php 
                            Utils::get_value_by_language( $post_event->sd_data['title'], 'DE', '<h3>', '</h3>', true); 
                            ?>
                        </a>
                    </div>
                    <div class="sd-event-container">
                        <div class="sd-event-props">
                            <?php
                            Utils::get_date( $post->sd_date_begin, $post->sd_date_end, '<div class="sd-event-date">' . __('<strong>Date: </strong>', 'hueman-7l-child'), '</div>', true);
                            Utils::get_facilitators($post_event->sd_data['facilitators'], '<div class="sd-event-facilitators"><strong>' . __('Facilitator: ', 'hueman-7l-child') . '</strong>', '</div>', true);
                            Utils::get_value_by_language($post->sd_data['priceInfo'], 'DE', '<div class="sd-event-price"><strong>' . __('Price: ', 'hueman-7l-child') . '</strong>', '</div>', true );
                            Utils::get_venue($post->sd_data['venue'], '<div class="sd-event-venue"><strong>' . __('Venue: ', 'hueman-7l-child') . '</strong>', '</div>', true);
                            ?>
                        </div>
                        <div class=sd-event-image>
                            <?php
                            Utils::get_img_remote(  Utils::get_value_by_language($post_event->sd_data['teaserPictureUrl']), '300', '', $alt = __('remote image failed', 'hueman-7l-child'), '', '', true);
                            ?>
                        </div>
                        <div class=sd-event-teaser>
                            <?php 
                            echo Utils::get_value_by_language($post_event->sd_data['teaser']) 
                            ?>
                            <div class="sd-event-more-link">
                                <a class="button" href="<?php echo esc_url(get_permalink($post->wp_event_id)); ?>">
                                    <?php esc_html_e('More', 'hueman-7l-child')?>
                                </a>
                            </div> 
                        </div>
                    </div>
                    
                </div>
            </div>
        <?php
        }?>
        <div class="has-text-align-center">
            <br><p><?php echo get_posts_nav_link();?></p>
        </div>
        <?php
	} else {
        ?>
        <div class="entry-header-inner section-inner small has-text-align-center">
            <h5>
                <?php
                _e('Sorry, no events for this date.', 'hueman-7l-child');
                ?>
            </h5>
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
?>
