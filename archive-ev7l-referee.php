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

<?php /* end of vanilla hueman archive.php v3.2.9 */ ?>
        <?php
          $first_letter = '';
          while ( have_posts() ): the_post(); ?>
          <?php /*get_template_part('content-standard');*/ ?>
          <?php
            $firstname = get_post_meta($post->ID, 'firstname', true);
            $lastname  = get_post_meta($post->ID, 'lastname',  true);
            $new_first_letter = substr($lastname, 0, 1);
            if ( $new_first_letter != $first_letter) {
              if ($first_letter != '') {
                echo '</ul>';
              }
              echo '<h2 class="firstlettername">' . $new_first_letter . '</h2>';
              echo '<ul class="names-list">';
              $first_letter = $new_first_letter;
            }
            echo '<li><a href="' . get_permalink() .'">' . $firstname . ' <strong>' . $lastname .  '</strong></a></li>';
          ?>
				<?php endwhile; ?>
<?php /* pickup vanilla hueman archive.php v3.2.9, but stuff removed */ ?>

			<?php get_template_part('parts/pagination'); ?>

		<?php endif; ?>

	</div><!--/.pad-->

</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
