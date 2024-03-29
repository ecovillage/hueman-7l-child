<?php
use Inc\Utils\TemplateUtils as Utils;
require_once( get_stylesheet_directory() . '/includes/SDTemplateUtils.php' );
use SDTemplateUtils as SDUtils;

?>
<?php
/*  Print the post date. Compatible with Google Structured data. Must be used in the WordPress loop
* @php return html string
/* ------------------------------------ */
?>
<?php /* end of vanilla hueman archive.php v3.3.4 */ ?>
<?php /* (TODO) Could check for post type and place the actual events dates here! */ ?>
<?php /* pickup vanilla hueman archive.php v3.3.4 */ ?>

<?php /* Leaving vanilla hueman 3.3.4 parts/post-list-author-date.php */ ?>
<?php /*both is_category and has_category seem to do what we want */ ?>
<?php if(!has_category('betriebe' && !has_category('kooperationsprojekte')) && !is_page()) { ?>
<?php /* Reentering vanilla hueman 3.3.4 parts/post-list-author-date.php */ ?>
<p class="post-date">
  <?php if(get_post_type() == "ev7l-referee") {
    echo "Referent*";
  } elseif(get_post_type() == "ev7l-event-category") {
    echo __("Veranstaltungsrubrik", "hueman-7l-child");
  } elseif(get_post_type() == "ev7l-event") { ?>
    <?php echo __("Veranstaltung von ", 'hueman-7l-child'); ?><?php echo date_i18n(__('D d.M. Y', 'hueman-7l-child'), get_post_meta($post->ID, 'fromdate', true)); ?>
    <?php echo __("bis", 'hueman-7l-child'); ?> <?php echo date_i18n(__('D d.M. Y', 'hueman-7l-child'), get_post_meta($post->ID, 'todate', true)); ?>
  <?php } elseif(get_post_type() == "sd_cpt_event") { ?>
    <?php
      echo __("Veranstaltung von ", 'hueman-7l-child');
      echo "&nbsp;";
      $date_post = SDUtils::get_first_date($post->sd_event_id);
  		Utils::get_date_span( $date_post->sd_date_begin, $date_post->sd_date_end, null, null, null, null, true);
    ?>
  <?php } elseif(get_post_type() == "sd_cpt_facilitator") { ?>
    <?php echo __("Referent*in", 'hueman-7l-child'); ?>
  <?php } else { ?>
    <time class="published updated" datetime="<?php the_time('Y-m-d H:i:s'); ?>"><?php the_time( get_option('date_format') ); ?></time>
  <?php } ?>
</p>

<?php if ( hu_is_checked('structured-data') ) : ?>
  <p class="post-byline" style="display:none">&nbsp;<?php _e('by','hueman'); ?>
    <span class="vcard author">
      <span class="fn"><?php the_author_posts_link(); ?></span>
    </span> &middot; Published <span class="published"><?php echo get_the_date( get_option('date_format') ); ?></span>
    <?php if( get_the_modified_date() != get_the_date() ) : ?> &middot; Last modified <span class="updated"><?php the_modified_date( get_option('date_format') ); ?></span><?php endif; ?>
  </p>
<?php endif ?>

<?php /* Leaving vanilla hueman 3.3.4 parts/post-list-author-date.php */ ?>
<?php } ?>
<?php /* Reentering vanilla hueman 3.3.4 parts/post-list-author-date.php */ ?>

