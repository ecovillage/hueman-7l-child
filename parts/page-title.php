<div class="page-title pad group">
  <?php //prints the relevant metas (cat, tag, author, date, ...) for a given context : home, single post, page, 404, search, archive...  ?>
	<?php if ( is_home() && hu_is_checked('blog-heading-enabled') ) : ?>
		<h2><?php echo hu_blog_title(); ?></h2>
	<?php elseif ( is_single() ): ?>
		<ul class="meta-single group">
			<li class="category"><?php the_category(' <span>/</span> '); ?></li>
			<?php if ( comments_open() && ( hu_is_checked( 'comment-count' ) ) ): ?>
			<li class="comments"><a href="<?php comments_link(); ?>"><i class="fa fa-comments-o"></i><?php comments_number( '0', '1', '%' ); ?></a></li>
      <?php endif; ?>
    </ul>

    <?php if( get_post_type( $post ) == 'podcast' ): ?>
      Ökodorf Sieben Linden Podcast
    <?php endif; ?>

	<?php elseif ( is_page() ): ?>
    <?php /* Leaving vanilla hueman 3.2.9 parts/page-title.php */ ?>
    <?php /* <h2><?php echo hu_get_page_title(); ?></h2> */ ?>
    <?php /* Poor mans breadcrumbs */ ?>
    <h2 class="breadcrumbs">
      <?php
        foreach ( array_reverse(get_ancestors($post->ID, 'page')) as $ancestor) {
          echo '<a href="' . get_page_link($ancestor) .'">' . get_page($ancestor)->post_title .  ' / </a>';
        }
      ?>
      <?php echo hu_get_page_title(); ?>
    </h2>
    <?php /* Re-entering vanilla hueman */ ?>
	<?php elseif ( is_search() ): ?>
		<h1><?php echo hu_get_search_title(); ?></h1>
	<?php elseif ( is_404() ): ?>
		<h1><?php echo hu_get_404_title(); ?></h1>
	<?php elseif ( is_author() ): ?>
		<h1><?php echo hu_get_author_title(); ?></h1>
	<?php elseif ( is_category() || is_tag() ): ?>
		<h1><?php echo hu_get_term_page_title(); ?></h1>
	<?php elseif ( is_day() || is_month() || is_year() ) : ?>
		<h1><?php echo hu_get_date_archive_title(); ?></h1>
	<?php else: ?>
    <?php if ( ! is_home() && ! hu_is_checked('blog-heading-enabled') ) : ?>
		  <h2><?php the_title(); ?></h2>
    <?php elseif ( is_post_type_archive() ) : ?>
      <h2><?php post_type_archive_title(); ?></h2>
	  <?php endif; ?>
	<?php endif; ?>

</div><!--/.page-title-->
