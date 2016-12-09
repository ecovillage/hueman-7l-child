<tr>
  <td class="datecol">
    <?php echo date_i18n('d.m.Y', get_post_meta($post->ID, 'fromdate', true)); ?>
    - <?php echo date_i18n('d.m.Y', get_post_meta($post->ID, 'todate', true)); ?>
  </td>
  <td class="eventcol">
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      <?php /* the_excerpt(); */ ?><br/>
      <?php
        $referees = referees_by_event($post->ID);
        if ($referees->have_posts() && $referees->post_count > 1) {
          // transform to array, implode
          echo "(mit ";
          while ($referees->have_posts()) {
            $referees->the_post();
            if (true) {//)$post->ID != $base_referee_id) {
            ?>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <?php
              // TODO extract, maybe use array and then combine.
              // In all its madness, this is proposed by official documentation:
              // https://codex.wordpress.org/Function_Reference/have_posts#Note
              if ($referees->current_post + 1 < $referees->post_count) { echo ' und '; }
            }
          }
          echo ")";
        }
        $referees->reset_postdata();
      ?>
    </a>
  </td>
</tr>

