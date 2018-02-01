<?php

add_action('admin_menu', 'h7lc_admin_menu');
add_action('admin_init', 'h7lc_settings_init');

function h7lc_admin_menu() {
  add_options_page(__('Event Registration Settings', 'hueman-7l-child'), // page title
    __('Event Registration Settings', 'hueman-7l-child'), // menu title
    'manage_options', //capability
    'h7lc_event_registration_settings', // slug
    'h7lc_options'); // callback
}

function h7lc_options() {
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }
  ?>
  <div class="wrap">
    <h1><?php echo __("7 Linden Hueman Child: Registration Options", 'hueman-7l-child'); ?></h1>
    <form method="post" action="options.php">
    <?php
      settings_fields("h7lc_registration_settings");
      do_settings_sections("h7lc_registration_settings");
      submit_button();
    ?>
    </form>
  </div>
<?php
}

/* The mail field */
function h7lc_host_mailfrom_field_display() {
?>
  <input type="text" name="h7lc_host_mailfrom_field" id="h7lc_host_mailfrom_field" value="<?php echo get_option('h7lc_host_mailfrom_field'); ?>" size="30"/>
<?php
}

/* The mail field */
function h7lc_host_mailreplyto_field_display() {
?>
  <input type="text" name="h7lc_host_mailreplyto_field" id="h7lc_host_mailreplyto_field" value="<?php echo get_option('h7lc_host_mailreplyto_field'); ?>" size="30"/>
<?php
}

/* The mail field */
function h7lc_host_mailnotify_field_display() {
?>
  <input type="text" name="h7lc_host_mailnotify_field" id="h7lc_host_mailnotify_field" value="<?php echo get_option('h7lc_host_mailnotify_field'); ?>" size="30"/>
<?php
}

// TODO other section for contac form id!

/* Callback for section description */
function h7lc_settings_section_callback() {
  echo __("Setting Section", 'hueman-7l-child');
}

function h7lc_settings_init() {
  //add_option('h7lc_host_mailfrom_field');
  //add_option('h7lc_host_mailreplyto_field');
  //add_option('h7lc_host_mailnotify_field');
  add_settings_section(
    'h7lc_registration_settings', // id
    __( 'Registration', 'hueman-7l-child' ),
    'h7lc_settings_section_callback',
    'h7lc_registration_settings'
  );
  add_settings_field(
    'h7lc_host_mailnotify_field', // id
    __('email adress of host to notify on new registration', 'hueman-7l-child'), // title
    'h7lc_host_mailnotify_field_display', // callback
    'h7lc_registration_settings', // page
    'h7lc_registration_settings' // section
  );
  add_settings_field(
    'h7lc_host_mailreplyto_field', // id
    __('email adress of host to reply to', 'hueman-7l-child'), // title
    'h7lc_host_mailreplyto_field_display', // callback
    'h7lc_registration_settings', // page
    'h7lc_registration_settings' // section
  );
  add_settings_field(
    'h7lc_host_mailfrom_field', // id
    __('email adress of host in the from field', 'hueman-7l-child'), // title
    'h7lc_host_mailfrom_field_display', // callback
    'h7lc_registration_settings', // page
    'h7lc_registration_settings' // section
  );
  register_setting('h7lc_registration_settings', 'h7lc_host_mailfrom_field');
  register_setting('h7lc_registration_settings', 'h7lc_host_mailreplyto_field');
  register_setting('h7lc_registration_settings', 'h7lc_host_mailnotify_field');
}

