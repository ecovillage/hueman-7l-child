<?php
use Inc\Utils\TemplateUtils as Utils;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array('group', 'grid-item') ); ?>>
	<div class="post-inner post-hover">

		<div class="post-thumbnail">
      <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <?php
        if ($post->post_type == 'sd_cpt_event') {
        	$img_url = wp_strip_all_tags(Utils::get_value_by_language($post->sd_data['headerPictureUrl']));
          echo '<img src="'.$img_url.'" class="attachment-thumb-medium size-thumb-medium wp-post-image" loading="lazy" sizes="(max-width: 520px) 100vw, 520px" width="520" height="245">';
        } else { ?>
				  <?php hu_the_post_thumbnail('thumb-medium'); ?>
				  <?php if ( has_post_format('video') && !is_sticky() ) echo'<span class="thumb-icon"><i class="fa fa-play"></i></span>'; ?>
				  <?php if ( has_post_format('audio') && !is_sticky() ) echo'<span class="thumb-icon"><i class="fa fa-volume-up"></i></span>'; ?>
          <?php if ( is_sticky() ) echo'<span class="thumb-icon"><i class="fa fa-star"></i></span>'; ?>
          <?php
        }
        ?>
			</a>
			<?php if ( comments_open() && ( hu_is_checked( 'comment-count' ) ) ): ?>
				<a class="post-comments" href="<?php comments_link(); ?>"><span><i class="fa fa-comments-o"></i><?php comments_number( '0', '1', '%' ); ?></span></a>
			<?php endif; ?>
		</div><!--/.post-thumbnail-->

		<div class="post-meta group">
<?php /* Leaving vanilla hueman 3.3.4 content.php */ ?>
      <?php if(!has_category('betriebe')) { ?>
			<p class="post-category"><?php the_category(' / '); ?></p>
      <?php } ?>
<?php /* Reentering vanilla hueman 3.3.4 content.php */ ?>
			<?php get_template_part('parts/post-list-author-date'); ?>
		</div><!--/.post-meta-->

		<h2 class="post-title entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2><!--/.post-title-->

		<?php if (hu_get_option('excerpt-length') != '0'): ?>
		<div class="entry excerpt entry-summary">
			<?php the_excerpt(); ?>
		</div><!--/.entry-->
		<?php endif; ?>

	</div><!--/.post-inner-->
</article><!--/.post-->
