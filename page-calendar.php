<?php get_header(); ?>

<section class="content">

<?php /* Leaving hueman vanilla 3.2.9 page.php */ ?>
  <?php $eventyear  = get_query_var('eventyear', date('Y')); ?>
  <?php $eventmonth = get_query_var('eventmonth'); ?>
<?php /* Re-entering hueman vanilla 3.2.9 page */ ?>

<?php /* hueman vanilla 3.2.9 page.php 
	<?php hu_get_template_part('parts/page-title'); ?>
*/ ?>

<?php /* Leaving hueman vanilla 3.2.9 page.php */ ?>
  <div class="page-title pad group">
    <?php if ($eventyear && !$eventmonth) { ?>
      <h2>Veranstaltungen in <?php echo $eventyear; ?></h2>
    <?php } elseif ($eventmonth && !$eventyear) { ?>
      <h2>Veranstaltungen im <?php echo date_i18n('F', $eventmoth); ?> - aktuelles Jahr</h2>
    <?php } elseif ($eventmonth && $eventyear) { ?>
      <h2>Veranstaltungen im <?php echo date_i18n('F', $eventmonth); ?> <?php echo $eventyear ; ?></h2>
    <?php } else { ?>
      <h2>- <?php the_title(); ?></h2>
    <?php } ?>
  </div><!--/.page-title-->
<?php /* Re-entering hueman vanilla 3.2.9 page */ ?>

	<div class="pad group">

		<?php while ( have_posts() ): the_post(); ?>

			<article <?php post_class('group'); ?>>

				<?php hu_get_template_part('parts/page-image'); ?>

				<div class="entry themeform">
          <?php the_content(); ?>

<?php /* Leaving hueman vanilla 3.2.9 page.php */ ?>

            <?php
              /*
              echo $eventyear;
              echo $eventmonth;
              */
            if ($eventyear && !$eventmonth) {
              $events = events_in_year($eventyear);
              //echo 'Year only';
            } elseif ($eventmonth && !$eventyear) {
              $events = events_in_year_month(date('Y'), $eventmonth);
              //echo 'Month only';
              // Should probably default to current year.  Can we default that in the get_query_var / eventyear assignment?
            } elseif ($eventmonth && $eventyear) {
              $events = events_in_year_month($eventyear, $eventmonth);
              //echo 'Year and month';
            } else {
              //echo 'Default (no year, no month)';
            }
            ?>

            <?php
                if ($events->have_posts() ) { ?>
                <ul class="calendar-events">
                  <?php
                  while ( $events->have_posts() ) {
                    $events->the_post(); ?>
                    <li class="calendar-event-row">
                      <div class="grid one-third">
                      <?php if ( has_post_thumbnail() ): ?>
                        <?php hu_the_post_thumbnail('thumb-medium'); ?>
                      <?php else: ?>
                        <?php hu_the_post_thumbnail('thumbnail'); ?>
                      <?php endif; /* placeholder would be cool */ ?>
                      </div>
                      <div class="grid two-third last">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
                        <div class="event-dates-small">
                          von <?php echo date_i18n('d.M.Y', get_post_meta($post->ID, 'fromdate', true)); ?>
                          bis <?php echo date_i18n('d.M.Y', get_post_meta($post->ID, 'todate', true)); ?>
                        </div>
                        <?php the_excerpt(); ?>
                      </div>
                    </li>
            <?php
                }
              echo '</ul>';
              } else { ?>
                Keine Veranstaltungen im gewählten Zeitraum
            <?php } ?>
  <?php wp_reset_postdata(); ?>

          <?php
            // Test links
            // an associative array containing the query var and its value
            $params = array('eventyear' => date('Y'));
          ?>
          <?php /*
          <!-- pass in the $params array and the URL -->
          <a href="<?php echo add_query_arg($params, '/calendar'); ?>"> Aktuelles Jahr (<?php echo date('Y'); ?>)</a>
          */ ?>
          <?php
            $params = array('eventmonth' => '11', 'eventyear' => '2016');
          ?>
          <?php /*
          <a href="<?php echo add_query_arg($params, '/calendar'); ?>"> 2016/8</a>
          */ ?>


<?php /* Re-entering hueman vanilla 3.2.9 page */ ?>

					<div class="clear"></div>
        </div><!--/.entry-->

			</article>

			<?php if ( hu_is_checked('page-comments') ) { comments_template('/comments.php',true); } ?>

		<?php endwhile; ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
