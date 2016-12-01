<?php get_header(); ?>

<section class="content">

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
  <div class="page-title pad group">
    <h2>Rubrik - <?php the_title(); ?></h2>
  </div><!--/.page-title-->
<?php /* continue vanilla hueman archive.php v3.2.9 */ ?>

<?php /* removed from vanilla hueman archive.php v3.2.9
	<?php hu_get_template_part('parts/page-title'); ?>
*/ ?>

	<div class="pad group">

		<?php while ( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?>>

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
        <div class="grid one-half">
        </div>
        <div class="grid one-half last">
          <?php the_content(); ?>
        </div>

					<?php if ( has_post_thumbnail() ): ?>
						<?php hu_the_post_thumbnail('thumb-medium'); ?>
					<?php elseif ( hu_is_checked('placeholder') ): ?>
            <?php /*Code does not work here:  hu_print_placeholder_thumb('thumb-medium'); */ ?>
          <?php endif; ?>

          <div class="clear"></div>
          <div class="hr"></div>

          <?php
            $events = upcoming_events_in_category($post->ID);

            if ( $events->have_posts() ) {
              ?>
              <div class="upcoming-events">
                <table>

              <?php
              // Loop vars to find month changes.
              $monthyear  = -1;

              while ( $events->have_posts() ) {
                $events->the_post();
                if ($monthyear != date_i18n('F Y', get_post_meta($post->ID, 'fromdate', true))) {
                  $monthyear = date_i18n('F Y', get_post_meta($post->ID, 'fromdate', true));
                  echo '</table><h2>'.$monthyear.'</h2><table>';
                }
                get_template_part('parts/event_row');
             }
              echo "</table>";
              echo "</div><!-- .upcoming-events -->";
            }
            // else: No upcoming events
            /* Restore original Post data */
            wp_reset_postdata();
          ?>

            <?php
              $events = past_events_in_category($post->ID);

              if ( $events->have_posts() ) {
?>
          <div class="past-events">
            <h2>Seminare in der Vergangenheit</h2>
            <?php
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
                 ?>
                </table>
                </div><!-- .past-events -->
              <?php
              }
              // else: No upcoming events
              /* Restore original Post data */
              wp_reset_postdata();
            ?>

<?php /* pickup vanilla hueman archive.php v3.2.9 */ ?>

<?php /* vanilla hueman single.php v3.2.9:
				<div class="post-inner group">

          <?php hu_get_template_part('parts/single-heading'); ?>

					<?php if( get_post_format() ) { get_template_part('parts/post-formats'); } ?>

					<div class="clear"></div>

					<div class="<?php echo implode( ' ', apply_filters( 'hu_single_entry_class', array('entry','themeform') ) ) ?>">
						<div class="entry-inner">
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
 */ ?>

			</article><!--/.post-->
		<?php endwhile; ?>

    <div class="clear"></div>


<?php /* vanilla hueman single.php v3.2.9:

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
*/ ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
