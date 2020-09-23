<h1 class="post-title entry-title"><?php the_title(); ?></h1>
<?php if (!(is_singular ( array ('ev7l-event', 'ev7l-event-category',
  'ev7l-referee', 'sd_cpt_event', 'sd_cpt_date', 'sd_cpt_facilitator' ) ) ) ) {
  get_template_part('parts/single-author-date');
}
?>
