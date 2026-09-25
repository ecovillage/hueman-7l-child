<?php
/*
* Template Name: Fullwidth-Template
*/
?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<!-- START Part 1 - get_header('fullwidth-template'); -->
    <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php wp_head(); ?>


    <link rel="stylesheet" href="/wp-content/uploads/7l-landing/css/aos.css" />

    </head>

    <body <?php body_class(); ?>>

    <div id="wrapper" class="">

    <?php do_action('__before_header') ; ?>
<!-- END Part 1 - get_header('fullwidth-template'); -->

<!-- START hu_get_template_part('parts/header-main-fullwidth'); -->
    <?php
        //Model definition
        //MENUS
        $class_map = array(
            'main_menu'   => 'main-menu-mobile-on',
            'top_menu'    => 'top-menu-mobile-on',
            'both_menus'  => 'both-menus-mobile-on'
        );
        $mobile_menu_opt = hu_get_option( 'header_mobile_menu_layout' );
        $mobile_menu_class = array_key_exists( $mobile_menu_opt, $class_map ) ? $class_map[ $mobile_menu_opt ] : 'main-menu-mobile-on';

        //HEADER IMAGE
        $_header_img_src = get_header_image();// hu_get_img_src_from_option('header-image');
        $_has_header_img = false != $_header_img_src && ! empty( $_header_img_src );

        //WHEN DO WE DISPLAY THE REGULAR TOP NAV
        //=> when there's a topbar menu assigned or when the default page menu option "default-menu-header" is checked ( not for multisite @see issue on github )
        //@see hu_is_topbar_displayed() in init-functions.php

        //WHEN DO WE DISPLAY THE HEADER NAV ?
        // => when there's a header menu assigned or when the fallback callback function is set ( with a filter, used in prevdem scenario typically )
        //@see hu_is_header_nav_displayed() in init-functions.php
        //( ! wp_is_mobile() && hu_has_nav_menu( 'header' ) ) || in_array( $mobile_menu_opt, array( 'main_menu', 'both_menus' ) )

        //HEADER CSS CLASSES
        $header_classes = array(
            $mobile_menu_class,
            'both_menus' == $mobile_menu_opt ? 'two-mobile-menus' : 'one-mobile-menu',
            hu_get_option( 'header_mobile_menu_layout' ),
            hu_is_checked( 'header-ads-desktop' ) ? 'header-ads-desktop' : '',
            hu_is_checked( 'header-ads-mobile' ) ? 'header-ads-mobile' : ''
        );

    ?>
    <header id="header" class="<?php echo apply_filters( 'hu_header_classes', implode(' ', $header_classes ) ); ?>">
    <?php if ( 'both_menus' != $mobile_menu_opt ) : //if both menus is the user option, we won't use the mobile navigation ?>
        <?php get_template_part('parts/header-nav-mobile'); ?>
    <?php endif; ?>

    <?php if ( hu_is_topbar_displayed() ) : ?>
        <?php get_template_part( 'parts/header-nav-topbar' ); ?>
    <?php endif; ?>

    <div class="container fullwidth group">
        <?php do_action('__before_after_container_inner'); ?>
        <div class="container-inner fullwidth">

        <?php if ( ! $_has_header_img || ! hu_is_checked( 'use-header-image' ) ) : ?>

                <div class="group pad central-header-zone">
                    <div class="logo-tagline-group">
                        <?php hu_print_logo_or_title();//gets the logo or the site title ?>
                        <?php if ( hu_is_checked('site-description') ) : ?>
                            <p class="site-description"><?php hu_render_blog_description() ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if ( hu_is_checked('header-ads') ) : ?>
                        <div id="header-widgets">
                            <?php hu_print_widgets_in_location( 'header-ads' ); ?>
                        </div><!--/#header-ads-->
                    <?php endif; ?>
                </div>

        <?php else :  ?>
            <div id="header-image-wrap">
                <?php hu_render_header_image( $_header_img_src ); ?>
            </div>
        <?php endif; ?>

        <?php if ( hu_is_header_nav_displayed() ) : ?>
            <?php get_template_part('parts/header-nav-main'); ?>
        <?php endif; ?>

        </div><!--/.container-inner-->
        <?php do_action('__header_after_container_inner'); ?>
    </div><!--/.container-->

    </header><!--/#header-->
<!-- END hu_get_template_part('parts/header-main-fullwidth'); -->

<!-- START Part 2 - get_header('fullwidth-template'); -->
  <?php do_action('__after_header') ; ?>

  <div class="container fullwidth" id="page">


    <div class="container-inner fullwidth">
      <?php do_action('__before_main') ; ?>
      <div class="main">
        <div class="main-inner group">
          <?php do_action('__before_content') ; ?>

<!-- END Part 2 - get_header('fullwidth-template'); -->

<!-- START - hu_get_content( 'tmpl/page-tmpl'); -->

    <?php while ( have_posts() ): the_post(); ?>

    <article <?php post_class('group'); ?>>

        <?php if ( hu_is_checked( 'singular-page-featured-image' ) ) { hu_get_template_part('parts/page-image'); } ?>

        <div class="entry themeform">
        <?php the_content(); ?>
        <nav class="pagination group">
            <?php
            //Checks for and uses wp_pagenavi to display page navigation for multi-page posts.
            if ( function_exists('wp_pagenavi') )
                wp_pagenavi( array( 'type' => 'multipart' ) );
            else
                wp_link_pages(array('before'=>'<div class="post-pages">'.__('Pages:','hueman'),'after'=>'</div>'));
            ?>
        </nav><!--/.pagination-->
        <div class="clear"></div>
        </div><!--/.entry-->

    </article>

    <?php if ( hu_is_checked('page-comments') ) { comments_template('/comments.php',true); } ?>

    <?php endwhile; ?>

<!-- END - hu_get_content( 'tmpl/page-tmpl'); -->

<!-- DISABLED get_sidebar(); -->

<?php get_footer(); ?>
</html>
