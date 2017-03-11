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
<?php if(!has_category('betriebe')) { ?>
<?php /* Reentering vanilla hueman 3.3.4 parts/post-list-author-date.php */ ?>
<p class="post-date">
  <time class="published updated" datetime="<?php the_time('Y-m-d H:i:s'); ?>"><?php the_time( get_option('date_format') ); ?></time>
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

