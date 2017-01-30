<?php get_header(); ?>

<section class="content">

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
  <div class="page-title pad group">
    <h2>Referent* - <?php the_title(); ?></h2>
  </div><!--/.page-title-->
<?php /* continue vanilla hueman archive.php v3.2.9 */ ?>

<?php /* removed from vanilla hueman archive.php v3.2.9
	<?php hu_get_template_part('parts/page-title'); ?>
*/ ?>

	<div class="pad group">

		<?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?>>
				<div class="post-inner group">

          <?php hu_get_template_part('parts/single-heading'); ?>


					<?php if( get_post_format() ) { get_template_part('parts/post-formats'); } ?>

					<div class="clear"></div>

					<div class="<?php echo implode( ' ', apply_filters( 'hu_single_entry_class', array('entry','themeform') ) ) ?>">
						<div class="entry-inner">

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
              <div class="grid two-third">
                <?php the_content(); ?>
              </div>
              <div class="grid one-third last">
                <?php if ( has_post_thumbnail() ): ?>
                  <?php hu_the_post_thumbnail('medium'); ?>
                <?php elseif ( hu_is_checked('placeholder') ): ?>
                  <?php
                  /* Code does not work here:  hu_print_placeholder_thumb('thumb-medium');
                  *  Also nice trick would be to overwrite parts/post-list-authore-date !
                  */ ?>
                <?php endif; ?>
              </div>
      
                <div class="clear"></div>
                <?php $homepage = get_post_meta($post->ID, 'homepage', true); ?>
                <?php if (!empty($homepage) ): ?>
                <div>
                  <h2>Homepage</h2>
                  <a href="<?php echo $homepage;?>"><?php echo $homepage;?></a>
                </div>
                <?php endif; ?>
                <div class="hr"></div>
      
                <div class="event-listing-heading">
                  <h2>Veranstaltungen von und mit <?php echo the_title(); ?></h2>
                </div>
      
                <div class="upcoming-events">
                  <?php
                    $events = upcoming_events_by_referee($post->ID);
      
                    if ( $events->have_posts() ) {
                      echo '<ul class="referee-events">';
      
                      // Loop vars to find month changes.
                      $monthyear  = -1;
      
                      while ( $events->have_posts() ) {
                        $events->the_post();
                        $base_referee_id = $post->ID;
                        if ($monthyear != date_i18n('F Y', get_post_meta($post->ID, 'fromdate', true))) {
                          $monthyear = date_i18n('F Y', get_post_meta($post->ID, 'fromdate', true));
                          echo '</ul><h2>'.$monthyear.'</h2><ul class="referee-events">';
                        }
                        get_template_part('parts/event_list_line');
                      }
                      echo "</ul>";
                    }
                    else {
                      echo "Derzeit keine Veranstalungen in naher Zukunft geplant.";
                    }
                      /* Restore original Post data */
                      wp_reset_postdata();
                    ?>
                </div><!-- .upcoming-events -->

                <?php
                  $events = past_events_by_referee($post->ID);

                  if ( $events->have_posts() ) {
                    echo '<div class="past-events">';
                    echo '<h2>Seminare in der Vergangenheit</h2>';
                    echo '<table>';

                    // Loop vars to find month changes.
                    $monthyear  = -1;

                    while ( $events->have_posts() ) {
                      $events->the_post();
                      if ($monthyear != date_i18n('F Y', get_post_meta($post->ID, 'fromdate', true))) {
                        $monthyear = date_i18n('F Y', get_post_meta($post->ID, 'fromdate', true));
                        echo "</table><h2 class='month-section'>".$monthyear."</h2><table>";
                      }
                      ?>
                        <tr>
                          <td class="datecol">
                            <?php echo date_i18n('d.m.Y', get_post_meta($post->ID, 'fromdate', true)); ?>
                            - <?php echo date_i18n('d.m.Y', get_post_meta($post->ID, 'todate', true)); ?>
                          </td>
                          <td class="eventcol">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <?php /* the_excerpt(); */ ?>
                          </td>
                        </tr>
                <?php }
                    echo '</table>';
                    echo '</div> <!-- .past-events -->';
                  }
                  /* Restore original Post data */
                  wp_reset_postdata();
                ?>

<?php /* pickup vanilla hueman archive.php v3.2.9 */ ?>

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

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
    <?php /*if ( '1' != hu_get_option( 'related-posts' ) ) { get_template_part('parts/related-posts'); }*/ ?>
<?php /* pickup vanilla hueman archive.php v3.2.9 */ ?>

		<?php if ( hu_is_checked('post-comments') ) { comments_template('/comments.php',true); } ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
