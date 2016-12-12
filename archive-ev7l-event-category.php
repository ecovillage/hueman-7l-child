<?php get_header(); ?>

<section class="content">

	<?php hu_get_template_part('parts/page-title'); ?>

	<div class="pad group">

		<?php if ((category_description() != '') && !is_paged()) : ?>
			<div class="notebox">
				<?php echo category_description(); ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
      <ul class="event-categories">
				<?php while ( have_posts() ): the_post(); ?>
          <li class="event-category-row">
            <div class="grid one-third">
              <?php hu_the_post_thumbnail('thumbnail'); ?>
            </div>
            <div class="grid two-third last">
              <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <?php the_excerpt(); ?>
            </div>
          </li>
				<?php endwhile; ?>
      </ul>
			<?php get_template_part('parts/pagination'); ?>

		<?php endif; ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
