<script type="text/javascript">
  // Load flexslider once the first slide image is ready (or immediately if it's already cached)
  jQuery(function ($) {
    var $slider = $('#flexslider-featured');
    var image = $slider.find('img').filter(':first').get(0);

    function initSlider() {
      $slider.flexslider({
        animation: "slide",
        useCSS: false, // Fix iPad flickering issue
        directionNav: true,
        controlNav: true,
        pauseOnHover: true,
        animationSpeed: 400,
        smoothHeight: true,
        touch: 1,
        slideshow: true,
        slideshowSpeed: 5000
      });
      $slider.trigger('featured-slider-ready');
    }

    if (!image || (image.complete && image.naturalWidth > 0)) {
      initSlider(); // Bild schon geladen (z. B. aus dem Cache) -> sofort starten
    } else {
      image.addEventListener('load', initSlider, { once: true });
      image.addEventListener('error', initSlider, { once: true }); // nicht ewig hängen, falls Bild kaputt ist
    }
  });
</script>

<?php
  // Query featured entries
  $localized_category_name = 'news';
  if ($lang == 'en') {
    $localized_category_name = 'news-en';
  }
  $featured = new WP_Query(
    array(
      'no_found_rows' => false,
      'update_post_meta_cache' => false,
      'update_post_term_cache' => false,
      'ignore_sticky_posts' => 1,
      'category_name' => $localized_category_name,
      'posts_per_page' => 7
    )
  );
?>

<?php if ( $featured->have_posts() ): ?>

  <div class="featured flexslider" id="flexslider-featured">
    <ul class="slides">
      <?php
      $count = -1;
      while ( $featured->have_posts() ): $featured->the_post(); $count++;
      if ($count == 0) {
      ?>
      <li style="display: initial;">
        <?php } else { ?>
      <li>
      <?php }
        get_template_part('content-featured');
      ?>
      </li>
      <?php endwhile; ?>
    </ul>
  </div><!--/.featured-->

<?php endif; ?>
<?php wp_reset_postdata(); ?>
