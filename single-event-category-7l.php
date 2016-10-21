<?php get_header(); ?>

<section class="content">

	<?php hu_get_template_part('parts/page-title'); ?>

	<div class="pad group">

		<?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?>>
				<div class="post-inner group">

          <?php hu_get_template_part('parts/single-heading'); ?>

					<?php if( get_post_format() ) { get_template_part('parts/post-formats'); } ?>

					<div class="clear"></div>

					<div class="<?php echo implode( ' ', apply_filters( 'hu_single_entry_class', array('entry','themeform') ) ) ?>">
            <div class="entry-inner">

<?php /* Leaving vanilla hueman */ ?>
					<?php if ( has_post_thumbnail() ): ?>
						<?php hu_the_post_thumbnail('thumb-medium'); ?>
					<?php elseif ( hu_is_checked('placeholder') ): ?>
						<?php hu_print_placeholder_thumb(); ?>
					<?php endif; ?>

<!-- get_query_var -->
              <?php the_content(); ?>
                  Seminare: in future (asc), dann in vergangenheit (desc)
                  <?php
                    /* Non-reciprocal many-to-many models are possible with this, too. */
                    $events = new WP_Query( array(
                      'post_type' => 'event-7l',
                      'post__in' => get_post_meta( $post, 'event_ids', true ),
                      'nopaging' => true) );
                    if ( $events->have_posts() ) {
                      while ( $events->have_posts() ) {
                        $events->the_post();
                        ?>
                          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                          von <?php echo date('d.M.Y', get_post_meta($post->ID, 'fromdate', true)); ?> bis <?php echo date('d.M.Y', get_post_meta($post->ID, 'todate', true)); ?>
                            <?php the_excerpt(); ?></li>
                  <?php }}
                    /* Restore original Post data */
                    wp_reset_postdata();
                  ?>
                  Seminare: in vergangenheit (desc)

<?php /* Re-entering vanilla hueman */ ?>

							<?php the_content(); ?>
							<nav class="pagination group">
                <?php
                  //Checks for and uses wp_pagenavi to display page navigation for multi-page posts.
                  if ( function_exists('wp_pagenavi') )
                    wp_pagenavi( array( 'type' => 'multipart' ) );
                  else
                    wp_link_pages(array('before'=>'<div class="post-pages">'.__('Pages:','hueman'),'after'=>'</div>'));
                ?>
              </nav><!--/.pagination-->
						</div>

            <?php do_action( 'hu_after_single_entry_inner' ); ?>

						<div class="clear"></div>
					</div><!--/.entry-->

				</div><!--/.post-inner-->
			</article><!--/.post-->
		<?php endwhile; ?>

		<div class="clear"></div>

		<?php the_tags('<p class="post-tags"><span>'.__('Tags:','hueman').'</span> ','','</p>'); ?>

		<?php if ( ( hu_is_checked( 'author-bio' ) ) && get_the_author_meta( 'description' ) ): ?>
			<div class="author-bio">
				<div class="bio-avatar"><?php echo get_avatar(get_the_author_meta('user_email'),'128'); ?></div>
				<p class="bio-name"><?php the_author_meta('display_name'); ?></p>
				<p class="bio-desc"><?php the_author_meta('description'); ?></p>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

		<?php if ( 'content' == hu_get_option( 'post-nav' ) ) { get_template_part('parts/post-nav'); } ?>

		<?php if ( '1' != hu_get_option( 'related-posts' ) ) { get_template_part('parts/related-posts'); } ?>

		<?php if ( hu_is_checked('post-comments') ) { comments_template('/comments.php',true); } ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
