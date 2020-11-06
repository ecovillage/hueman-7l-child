<?php
/*
Template Name: Liste der Referent*innen (SDHC7L)
*/
?>

<?php get_header(); ?>
<?php

$custom_query = new WP_Query(
  array(
    'post_type'     => 'sd_cpt_facilitator',
    'post_status'   => 'publish',
    'meta_key'      => 'sd_facilitator_id',
    'posts_per_page' => 6000, //nopaging => true
  )
);

$facilitator_list = $custom_query->posts; // alt: get_posts(Q)

usort( $facilitator_list, function ( $f1, $f2 ) {
  return strtolower( $f1->sd_data['lastName'] ) <=> strtolower( $f2->sd_data['lastName'] );
});
?>

<section class="content">

  <?php hu_get_template_part('parts/page-title'); ?>

  <div class="pad group">

		<?php if ((category_description() != '') && !is_paged()) : ?>
			<div class="notebox">
				<?php echo category_description(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $custom_query->have_posts() ) : ?>

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
  <div class="page-image">
  	<div class="image-container">
      <img src="/wp-content/uploads/2017/05/Referent_innen-720x340.jpg" class="attachment-thumb-large size-thumb-large wp-post-image" alt="" srcset="/wp-content/uploads/2017/05/Referent_innen-720x340.jpg 720w, /wp-content/uploads/2017/05/Referent_innen-520x245.jpg 520w" sizes="(max-width: 720px) 100vw, 720px" height="340" width="720"/>
  		<?php
  			echo '<div class="page-image-text">';
        /** Leaving vanilla 3.3.4 hueman theme
          if ( isset($caption) && $caption ) echo '<div class="caption">'.$caption.'</div>';
         */
        echo '<div class="caption"><h1>';
        //echo the_title();
        echo __('Referent*innen', 'hueman-7l-child');
        echo '</h1></div>';
        /** vanilla 3.3.4 hueman theme
          if ( isset($description) && $description ) echo '<div class="description"><i>'.$description.'</i></div>';
         */
  			echo '</div>';
  		?>
  	</div>
  </div><!--/.page-image-->
        <?php
          $first_letter = '';
          $break_point  = $custom_query->post_count / 2;
          $last_post_pos  = 0;
          $index        = 0; // TODO this cannot be right
          echo '<div class="grid one-half">';

          foreach( $facilitator_list as $facilitator ) {
          ?>

          <?php /*get_template_part('content-standard');*/ ?>
          <?php
            $firstname = $facilitator->sd_data['firstName'];
            $lastname  = $facilitator->sd_data['lastName'];
            $new_first_letter = substr($lastname, 0, 1);
            if ( $new_first_letter != $first_letter) {
              if ($first_letter != '') {
                echo '</ul>';
              }
              if ($last_post_pos < $break_point && $index > $break_point) {
                echo '</div><div class="grid one-half last">';
              }
              echo '<h2 class="firstlettername">' . $new_first_letter . '</h2>';
              echo '<ul class="names-list">';
              $first_letter = $new_first_letter;
            }
            $index += 1;
            echo '<li><a href="' . get_post_permalink( $facilitator ) .'">' . $firstname . ' <strong>' . $lastname .  '</strong></a></li>';
            $last_post_pos = $index;

          ?>
        <?php } ?>
        </div><!--grids-->
<?php /* pickup vanilla hueman archive.php v3.2.9, but stuff removed */ ?>

			<?php get_template_part('parts/pagination'); ?>

		<?php endif; ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>

