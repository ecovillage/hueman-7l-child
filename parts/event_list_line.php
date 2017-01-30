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

