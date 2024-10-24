<?php
/**
* Template Name: _7L-Landingpage_alternate
* Jens: This template will only display the content you entered in the page editor and is meant for raw html. We use it for pages that are styled without our theme, e.g. our landing page.
*/

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="googlebot" content="index,follow,snippet,archive">
<?php wp_head(); ?>

<!-- Jens: let's pull in some stylesheets -->
<link rel="stylesheet" href="/wp-content/uploads/7l-landing/css/style.css" />
<link rel="stylesheet" href="/wp-content/uploads/7l-landing/css/utilities.css" />
<link rel="stylesheet" href="/wp-content/uploads/7l-landing/css/aos.css" />
<link rel="stylesheet" href="/wp-content/uploads/7l-landing/css/responsive.css" />
<link rel="stylesheet" href="/wp-content/uploads/7l-landing/css/fa-all.css" />

</head>

<body class="cleanpage">
<?php while ( have_posts() ): the_post(); ?>
		<?php the_content(); ?>
		<?php endwhile; ?>
<?php wp_footer(); ?>

<!-- Jens: let's pull in some js -->
<script src="/wp-content/uploads/7l-landing/js/aos.js"></script>
<script>
    AOS.init({ disable: "mobile" });
</script>

</body>
</html>
