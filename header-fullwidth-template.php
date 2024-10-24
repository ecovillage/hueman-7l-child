<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
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

  <?php hu_get_template_part('parts/header-main-fullwidth'); ?>

  <?php do_action('__after_header') ; ?>

  <div class="container fullwidth" id="page">

  
    <div class="container-inner fullwidth">
      <?php do_action('__before_main') ; ?>
      <div class="main">
        <div class="main-inner group">
          <?php do_action('__before_content') ; ?>
