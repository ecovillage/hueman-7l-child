<?php get_header(); ?>

<section class="content">

	<?php hu_get_template_part('parts/page-title'); ?>

	<div class="pad group">

		<?php while ( have_posts() ): the_post(); ?>

      <article <?php post_class('group'); ?>>

				<div class="entry themeform">
          <h1><?php the_title(); ?></h1>

				  <?php hu_get_template_part('parts/page-image'); ?>

					<?php the_content(); ?>
					<div class="clear"></div>
				</div><!--/.entry-->

			</article>

			<?php if ( hu_is_checked('page-comments') ) { comments_template('/comments.php',true); } ?>

		<?php endwhile; ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
