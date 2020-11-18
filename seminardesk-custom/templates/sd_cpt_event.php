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
 * The template for single post of CPT sd_cpt_event
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
  <h2><?php echo __('Ausgewähltes Seminar', 'hueman-7l-child'); ?></h2>
  </div><!--/.page-title-->
<?php /* Re-entering vanilla hueman */ ?>

<!-- SeminarDesk original template -->

<div class="pad group entry" id="site-content" role="main">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <header class="entry-header has-text-align-center">
                <div class="entry-header-inner section-inner medium">


    <div class="post-inner group">
      <?php /* Leaving vanilla theme to include image in standard post format. */ ?>
        <div class="post-format">
          <div class="image-container">
            <?php
              $url = Utils::get_value_by_language($post->sd_data['headerPictureUrl']);
              //Utils::get_img_remote( $url, '300', '', $alt = __('remote image failed', 'seminardesk'));
            ?>
            <img src="<?php echo $url; ?>" class="attachment-thumb-large size-thumb-large wp-post-image" alt="" loading="lazy" sizes="(max-width: 720px) 100vw, 720px" width="720" height="340">

            <?/*php if ( has_post_thumbnail() ) {
              hu_the_post_thumbnail('thumb-large', '', false);//no attr, no placeholder
              $url = Utils::get_value_by_language($post->sd_data['headerPictureUrl']);
              echo Utils::get_img_remote( $url, '300', '', $alt = __('remote image failed', 'seminardesk'));

              $caption = get_post(get_post_thumbnail_id())->post_excerpt;
              if ( isset($caption) && $caption ) echo '<div class="image-caption">'.$caption.'</div>';
            } */ ?>
          </div> <!-- .image-container -->
        </div> <!-- .post-format -->
    </div>



                    <?php 
                    Utils::get_value_by_language( $post->sd_data['title'], 'DE', '<h1 class="archive-title">', '</h1>', true); 
                    echo Utils::get_value_by_language($post->sd_data['subtitle']); 
                    ?>
                </div>
            </header>
            <div class="post-meta-wrapper post-meta-single post-meta-single-top">
                <p>
                  <?php
                    $additionalFields = $post->sd_data['additionalFields'];
                    // TODO and not empty
                    if ( isset($additionalFields)
                      && isset($additionalFields['Qualifikation Referent*in'])
                      && !empty($additionalFields['Qualifikation Referent*in']) ) {
                      echo $additionalFields['Qualifikation Referent*in'];
                    }
                  ?>
                </p>

                <p>
                  <?php
                    // $is_empty = array_filter($playerlist, 'strlen') == [];
                    $facilitators = Utils::get_facilitators($post->sd_data['facilitators']);
                    if ($facilitators) {
                        echo '<strong>';
                        _e( 'More about the facilitators: ', 'hueman-7l-child' );
                        echo '</strong>';
                        echo $facilitators;
                    }
                  ?>
                </p>

                <p>
                  <?php
                    // $is_empty = array_filter($playerlist, 'strlen') == [];
                    if ( isset($additionalFields)
                      && isset($additionalFields['Vorraussetzung für Teilnahme'])
                      && !empty($additionalFields['Vorraussetzung für Teilnahme']) ) {
                      echo '<strong>';
						          _e( "Voraussetzungen für Teilnahme:" );
                      echo '</strong>';
						          echo $additionalFields['Vorraussetzung für Teilnahme'];
                    }
                  ?>
                </p>

                <p>
                    <?php
                    echo Utils::get_value_by_language($post->sd_data['description']);
                    ?>
                </p>
<?php
                    $infoDatesPrices  = Utils::get_value_by_language($post->sd_data['infoDatesPrices']);
                    $infoBoardLodging = Utils::get_value_by_language($post->sd_data['infoBoardLodging']);
                    $infoMisc         = Utils::get_value_by_language($post->sd_data['infoMisc']);
                    $contactPersons   = Utils::get_value_by_language($post->sd_data['contactPersons']);

                    if ( $infoDatesPrices ) {
                      echo '<br><strong>';
                      _e( 'Hinweise zu Terminen und Preisen: ', 'hueman-7l-child' );
                      echo '</strong>';
                      echo $infoDatesPrices;
                    }
                    if ( $infoBoardLodging ) {
                      echo '<br><strong>';
                      _e( 'Hinweise zu Unterkunft und Verpflegung: ', 'hueman-7l-child' );
                      echo '</strong>';
                      echo $infoBoardLodging;
                    }
                    if ( $infoMisc ) {
                      echo '<br><strong>';
                      _e( 'Hinweise: ', 'hueman-7l-child' );
                      echo '</strong>';
                      echo $infoMisc;
                    }
                    if ( $contactPersons ) {
                      echo '<br><strong>';
                      _e( 'KontaktPersonen: ', 'hueman-7l-child' );
                      echo '</strong>';
                      echo $contactPersons;
                    }
?>

                <?php
                    // get list of all dates for this event
                    $status_lib = array(
                        'available'     => _e( 'Booking Available' ),
                        'fully_booked'  => _e( 'Fully Booked' ),
                        'limited'       => _e( 'Limited Booking' ),
                        'wait_list'     => _e( 'Waiting List' ),
                    );

                    $booking_list = Utils::get_event_dates_list( $post->sd_event_id, $status_lib );
                    $booking_url = esc_url( Utils::get_value_by_language( $post->sd_data['bookingPageUrl'] ?? null ) );

                    if ( $booking_list ) {
                        ?>
                        <h4>
                            <?php 
                            _e( 'List of available dates:', 'hueman-7l-child' );
                            ?>
                        </h4>
                        <p>
                        <?php
                        echo $booking_list;
                        
                        if ( !empty($booking_url) && $post->sd_data['registrationAvailable'] === true ) {
                            ?>

                            <br><p><button class="sd-modal-booking-btn">
                                <?php 
                                _e( 'Booking', 'hueman-7l-child' );
                                ?>
                            </button></p>
                            </p>

                            <?php
                        }
                    } else {
                        echo '<h4>';
                        _e('No dates for this event available :(', 'hueman-7l-child');
                        echo '</h4>';
                    }
                    ?>
            </div>

            <!-- BEGIN modal content -->
            <div class="sd-modal">
                <div class="sd-modal-content">
                    <span class="sd-modal-close-btn">&times;</span>
                    <h4 class="sd-modal-title"><?php _e('Booking', 'hueman-7l-child');?></h4>
                    <iframe class="sd-modal-booking" src="<?php echo $booking_url ?>/embed" title="Seminardesk Booking"></iframe>
                </div>
            </div>
            <!-- END modal content -->

            <?php
        }
    } else {
        ?>
        <div class="entry-header-inner section-inner small has-text-align-center">
            <h5>
                <?php
                _e('<strong>Sorry, event does not exist.</strong>', 'hueman-7l-child');
                ?>
            </h5>
            <br>
        </div>
        <?php
    }
    wp_reset_query();
    ?>

    <div id="question">
      <h3><?php echo __('Frage zur Veranstaltung stellen', 'hueman-7l-child'); ?> </h3>
      <?php echo do_shortcode('[contact-form-7 id="2325" title="Frage zu Veranstaltung"]'); ?>
    </div>
    <script type="text/javascript">
        document.getElementsByClassName("wpcf7-form")[0].elements['your-subject'].value = 'Frage zu Veranstaltung: <?php echo the_title(); ?> ';
    </script>

</div><!-- #site-content -->

<!-- End of SeminarDesk original template -->

</section>

<?php

get_sidebar();

get_footer();
?>
