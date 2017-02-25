<?php if ( has_post_thumbnail() ): ?>
  <div class="page-image">
  	<div class="image-container">
  		<?php hu_the_post_thumbnail('thumb-large', '', false );//no attr and no placeholder ?>
  		<?php
  			$caption = get_post(get_post_thumbnail_id())->post_excerpt;
  			$description = get_post(get_post_thumbnail_id())->post_content;
  			echo '<div class="page-image-text">';
        /** Leaving vanilla 3.3.4 hueman theme
          if ( isset($caption) && $caption ) echo '<div class="caption">'.$caption.'</div>';
         */
        echo '<div class="caption"><h1>';
        echo the_title();
        echo '</h1></div>';
        /** vanilla 3.3.4 hueman theme
          if ( isset($description) && $description ) echo '<div class="description"><i>'.$description.'</i></div>';
         */
  			echo '</div>';
  		?>
  	</div>
  </div><!--/.page-image-->
<?php endif; ?>
