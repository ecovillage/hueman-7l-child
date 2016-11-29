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
      <h2><?php echo $eventyear; ?> - Ganzes Jahr</h2>
    <?php } elseif ($eventmonth && !$eventyear) { ?>
      <h2><?php echo $eventmoth; ?> - Ganzer Monat aktuelles Jahr</h2>
    <?php } elseif ($eventmonth && $eventyear) { ?>
      <h2><?php echo $eventyear.$eventmoth; ?> - Ganzer Monat im Jahr</h2>
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
            // an associative array containing the query var and its value
            //$params = array('eventmonth' => '8', 'eventyear' => '2016');
            $params = array('eventyear' => date('Y'));
          ?>
          <!-- pass in the $params array and the URL -->
          <a href="<?php echo add_query_arg($params, '/calendar'); ?>"> Aktuelles Jahr (<?php echo date('Y'); ?>)</a>

            <?php echo $eventyear; ?>
            <?php echo $eventmonth; ?>
            <?php if ($eventyear && !$eventmonth) {
              $events = events_in_year($eventyear);
              echo 'Year only';
            } elseif ($eventmonth && !$eventyear) {
              echo 'Month only';
              // Should probably default to current year.  Can we default that in the get_query_var / eventyear assignment?
            } elseif ($eventmonth && $eventyear) {
              echo 'Year and month';
            } else {
              echo 'Default (no year, no month)';
            }
            ?>
            iterate over events.
            <?php
              if ($events->have_posts() ) {
                ?>
			          <ul id="tab-popular-1" class="alx-tab group thumbs-enabled">
                <?php
                while ( $events->have_posts() ) {
                  $events->the_post(); ?>
                  <li><div class="tab-item-thumbnail"> <! change css class, bigger (you may also like size is good)! -->
							<?php if ( has_post_thumbnail() ): ?>
								<?php hu_the_post_thumbnail('thumb-small'); ?>
							<?php else: ?>
                <?php /* this is a bug, isnt it? undefinde function: hu_print_placeholder_thumb( 'thumb-small' ); */ ?>
              <?php endif; /* display like in alx-tabs */ ?></div>
                   <div class="tab-item-inner group">
                     <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      von <?php echo date_i18n('d.M.Y', get_post_meta($post->ID, 'fromdate', true)); ?>
                      bis <?php echo date_i18n('d.M.Y', get_post_meta($post->ID, 'todate', true)); ?>
                      <?php the_excerpt(); ?>
                  </div>
                 </li>

            <?php
                }
            echo '</ul>';
            } ?>
  <?php wp_reset_postdata(); ?>

will put test links here

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
