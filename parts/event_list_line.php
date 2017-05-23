<li class="calendar-event-row">
  <div class="grid one-third">
  <?php if ( has_post_thumbnail() ): ?>
    <?php hu_the_post_thumbnail('thumb-medium'); ?>
  <?php else: ?>
    <?php /*hu_the_post_thumbnail('thumbnail');*/ ?>
  <?php endif; /* placeholder would be cool */ ?>
  </div>
  <div class="grid two-third last">
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
    <div class="event-dates-small">
      von <?php echo date_i18n('d.M.Y', get_post_meta($post->ID, 'fromdate', true)); ?>
      bis <?php echo date_i18n('d.M.Y', get_post_meta($post->ID, 'todate', true)); ?>
    </div>
    <?php
      $excerpt = get_the_excerpt($post);
      $referees = referees_by_event($post->ID);
      if ($referees->have_posts()) {
        // TODO transform to array and implode
        echo "<div class=\"referees\">(mit ";
        while ($referees->have_posts()) {
          $referees->the_post();
          ?>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          <?php
            // In all its madness, this is proposed by official documentation:
            // https://codex.wordpress.org/Function_Reference/have_posts#Note
            if ($referees->current_post + 1 < $referees->post_count) { echo ' und '; }
        }
        echo ")</div>";
      }
      $referees->reset_postdata();
      //wp_reset_postdata();
      //wp_reset_query();
    ?>
    <?php /*$post = $event; echo get_the_excerpt($event->ID);*/ ?>
    <?php echo $excerpt; ?>
  </div>
</li>

