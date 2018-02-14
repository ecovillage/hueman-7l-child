<?php
/**
 * Display a form that let users enter information to apply for an event participation.
 */

$response = "";
$success  = false;

$msg_missing_info   = __("Please provide all information", "hueman-7l-child");
$msg_email_invalid  = __("Please provide a valid email adress", "hueman-7l-child");
$msg_message_sent   = __("Message was sent. If you do not receive a confirmation mail in the next minutes please get in touch!", "hueman-7l-child");
$msg_message_not_sent = __("Message was not sent due to a technical error!", "hueman-7l-child");
$msg_need_tos       = __("You need to accept the tos and cancellation conditions", "hueman-7l-child");
$msg_registered     = __("Registration received. You should receive a mail within the next minutes. If not ...", "hueman-7l-child");
$msg_technical_error= __("There was a real technical error with your registration. Please contact ....", "hueman-7l-child");

/* Sets a sucessfull registration message. */
function registration_form_success($message) {
  global $response;
  $response = '<div class="success">'.$message.'</div>';
  global $success;
  $success  = true;
};

/* Sets a registration failure message. */
function registration_form_error($message) {
  global $response;
  $response = '<div class="error">'.$message.'</div>';
};

/* Send an email to the host, return true if success. */
function send_mail_to_host($registration) {
  global $event_todate;
  global $event_fromdate;

  $to = get_option('h7lc_host_mailnotify_field');
  $subject = __('Registration for ', 'hueman-7l-child');
  $subject = $subject.get_the_title().' ('.$event_fromdate.' - '.$event_todate.')';

  $headers = 'From: '. $registration['email'] . "\r\n" .
    'Reply-To: ' . $registration['email'] . "\r\n";


  // Mail template
  ob_start();
  get_template_part('includes/mails/registration_host');
  $body = ob_get_contents();
  ob_end_clean();
  error_log("mail to host send: ".$body);

  $sent = wp_mail($to, $subject, $body, $headers);

  error_log("mail to host send: ".$body);
  return $sent;
}

/* Send an email to the participant, return true if success. */
function send_mail_to_participant($registration) {
  global $event_todate;
  global $event_fromdate;

  $to = $registration['email'];
  # or $event->post_title;
  $subject = __('Anmeldung zum Seminar ', 'hueman-7l-child').get_the_title().' ('.$event_fromdate.' - '.$event_todate.')';

  ob_start();
  get_template_part('includes/mails/registration_participant');
  $body = ob_get_contents();
  ob_end_clean();

  $headers = array('From: '.get_option('h7lc_host_mailfrom_field'), 'Reply-To: '.get_option('h7lc_host_mailreplyto_field'));
  $sent = wp_mail($to, $subject, $body, $headers);

  error_log('Send mail to participant: '.$sent);

  return $sent;
}

// Access field in $_POST if it is set, return false otherwise.
function post_index_or_null($key) {
  return (isset($_POST[$key])) ? $_POST[$key] : false;
};

/* Echos <i> with fa-warning if given key not in posted inputs. */
function echo_fa_warning_if_unposted($key) {
  if (!empty($_POST) && (!isset($_POST[$key]) || empty($_POST[$key]))) {
    echo '<i class="fa fa-warning"></i>';
  }
}

/* Echos checked='checked' if room found in posted room choices. */
function echo_checked_room($roomname) {
  echo (isset($_POST['room_wish']) && in_array($roomname, $_POST['room_wish']) ? 'checked="checked"' : '');
}

/* Modifies registration such that it includes participant information. */
function set_participants(&$registration, $firstname, $lastname, $firstnames, $lastnames, $ages) {
  $children = array();
  $youths   = array();
  $adults   = array();

  $adults[] = $registration["firstname"].' '.$registration['lastname'];

  $registration['participant_data'][] = array();
  $registration['participant_data'][$adults[0]] = '';

  $min_length = min(count($lastnames), count($firstnames), count($ages));

  # Rules:
  #   > 18: adult
  #   > 12: youth
  #       : child
  for($i = 0; $i < $min_length; $i++) {
    $age       = $ages[$i];
    $firstname = $firstnames[$i];
    $lastname  = $lastnames[$i];
    if (!(empty($age) && empty($firstname) && empty($lastname))) {
      if(empty($lastname)) {
        $lastname = $registration["lastname"];
      }
      $fullname = $firstname.' '.$lastname;
      if ($age > 18) {
        $adults[] = $fullname;
      } elseif ($age > 12) {
        $youths[] = $fullname;
      } else {
        $children[] = $fullname;
      }
      $registration['participant_data'][$fullname] = $age;
    }
  }

  $registration['num_children'] = count($children);
  $registration['num_youth']    = count($youths);
  $registration['num_adults']   = count($adults);

  $registration['adults']   = $adults;
  $registration['youth']    = $youths;
  $registration['children'] = $children;
}

/* Writes a JSON file to be picked up by legacy software. Returns false if fails. */
function write_registration_json_file($registration) {
  # Need to split out childe adults, youths and respective _nums
  $registration_structure = array();
  $registration_structure['g_value'] = $registration;
  $g_meta = array();
  $g_meta['g_type'] = 'slseminar_booking_request';
  $registration_structure['g_meta'] = $g_meta;

  # .plugin_dir_path( __FILE__ ) .
  #  = WP_PLUGIN_DIR."

  $filename = "registrations/registration_slorg_".rand()."-".rand().".txt";
  $write_result = file_put_contents($filename, json_encode($registration_structure));

  error_log('writing '.$filename.':'. $write_result);

  return $write_result;
}

// User provided content
$firstname     = post_index_or_null('firstname');
$lastname      = post_index_or_null('lastname');
$street_and_no = post_index_or_null('street_and_no');
$zip           = post_index_or_null('zip');
$city          = post_index_or_null('city');
$country       = post_index_or_null('country');
$email         = post_index_or_null('email');
$phone         = post_index_or_null('phone');
$mobile        = post_index_or_null('mobile');
$comment       = post_index_or_null('participant_comment');
$accept_tos    = post_index_or_null('accept_tos');
$submitted     = post_index_or_null('submitted');
$rooms         = post_index_or_null('room_wish');
$lastnames     = post_index_or_null('lastnames');
$firstnames    = post_index_or_null('firstnames');
$ages          = post_index_or_null('ages');
$donation      = post_index_or_null('donation');
$donateamount  = post_index_or_null('donateamount');

#$registration[] = array(
$registration = array(
  'firstname'   => $firstname,
  'lastname'    => $lastname,
  'place'       => $city,
  'zip'         => $zip,
  'cellphone'   => $mobile,
  'telephone'   => $phone,
  'email'       => $email,
  'address'     => $street_and_no,
  'room_wishes' => $rooms,
  'comments'    => $comment,
  'country'     => $country,
  'l_seminar'   => $event_uuid,
  'timestamp'   => date_i18n('Ymd H:i'),
  'donation'    => $donation,
  'donateamount'=> $donateamount,
);

global $registration;
global $event_uuid;

set_participants($registration, $firstname, $lastname, $firstnames, $lastnames, $ages);

if ($submitted && !empty($_POST)) {
  if (!empty($_POST['url'])) {
    // Pseudo-spam protection
    error_log("spam event registration detected");
  }
  elseif (!$accept_tos) {
    registration_form_error($msg_need_tos);
  }
  elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    registration_form_error($msg_email_invalid);
  }
  elseif(!$firstname || !$lastname || !$street_and_no || !$zip || !$city) {
    registration_form_error($msg_missing_info);
  }
  else {
    // disabled for debugging 
    if (false && !write_registration_json_file($registration)) {
      error_log("error in writing registration file");
      registration_form_error($msg_technical_error);
    }
    elseif (send_mail_to_participant($registration)) {
      error_log("sent mail to participant");

      if (send_mail_to_host($registration)) {
        #registration_form_success("validation passed");
        error_log("sent mail to host");
        registration_form_success($msg_registered);
      }
      else {
        error_log("failed to send mail to host");
        registration_form_error("host mail failed");
      }
    } else {
        error_log("failed to send mail to participant");
        registration_form_error("participant mail failed");
    }
  }
}
?>
<?php /* var_dump($_POST); */ ?>

  <div id="registration">
    <?php if (!$success) { ?>

    <h2><?php echo __("Anmeldung", "hueman-7l-child"); ?></h2>
    <div id="response"><?php echo $response; ?></div>
    <br/>

    <br/>
    <form id="registration_form" action="<?php the_permalink(); ?>" charset="UTF-8" method="POST">
      <input type="hidden" name="seminar_id" value="$event_uuid"/>

      <div class="grid one-half">
        <?php echo_fa_warning_if_unposted("firstname"); ?>
        <label for="firstname"><?php echo __("Vorname", "hueman-7l-child"); ?>*</label>
        <input type="text" placeholder="<?php echo __('Vorname', "hueman-7l-child"); ?>" id="firstname" name="firstname"
          value="<?php echo esc_attr($firstname); ?>"/>

        <?php echo_fa_warning_if_unposted("lastname"); ?>
        <label for="lastname"><?php echo __('Nachname', "hueman-7l-child"); ?>*</label>
        <input type="text" placeholder="<?php echo __('Nachname', "hueman-7l-child"); ?>" id="lastname" name="lastname"
          value="<?php echo esc_attr($lastname); ?>"/>

        <?php echo_fa_warning_if_unposted("street_and_no"); ?>
        <label for="street_and_no"><?php echo __('Straße und Hausnummer', "hueman-7l-child"); ?>*</label>
        <input type="text" placeholder="<?php echo __('Straße und Hausnummer', "hueman-7l-child"); ?>" id="street_and_no" name="street_and_no"
          value="<?php echo esc_attr($street_and_no); ?>"/>

        <?php echo_fa_warning_if_unposted("zip"); ?>
        <label for="zip"><?php echo __('PLZ', "hueman-7l-child"); ?>*</label>
        <input type="text" placeholder="<?php echo __('PLZ', "hueman-7l-child"); ?>" id="zip" name="zip"
         value="<?php echo esc_attr($zip); ?>"/>

        <?php echo_fa_warning_if_unposted("city"); ?>
        <label for="city"><?php echo __('Ort', "hueman-7l-child"); ?>*</label>
        <input type="text" placeholder="<?php echo __('Ort', "hueman-7l-child"); ?>" id="city" name="city"
          value="<?php echo esc_attr($city); ?>"/>

        <label for="land"><?php echo __('Land', "hueman-7l-child"); ?></label>
        <input type="text" placeholder="<?php echo __('Land', "hueman-7l-child"); ?>" id="country" name="country"
          value="<?php echo esc_attr($country); ?>"/>
      </div>

      <div class="grid one-half last">
        <?php echo_fa_warning_if_unposted("email"); ?>
        <label for="email"><?php echo __('E-Mail', "hueman-7l-child"); ?>*</label>
        <input type="text" placeholder="<?php echo __('email', "hueman-7l-child"); ?>" id="email" name="email"
          value="<?php echo esc_attr($email); ?>"/>

        <label for="phone"><?php echo __('Telefonnummer', "hueman-7l-child"); ?></label>
        <input type="text" placeholder="0123 45678" id="phone" name="phone"
          value="<?php echo esc_attr($phone); ?>"/>

        <label for="mobile"><?php echo __('Handy-Nummer', "hueman-7l-child"); ?></label>
        <input type="text" placeholder="0123 45678" id="mobile" name="mobile"
          value="<?php echo esc_attr($mobile); ?>"/>

        <label for="participant_comment"><?php echo __('Bemerkung', "hueman-7l-child"); ?></label>
        <textarea placeholder="..." id="participant_comment" name="participant_comment" rows="7"><?php echo esc_attr($comment); ?></textarea>
      </div>

      <br class="clear"/>
      <br/>

      <script type="text/javascript">
        function addParticipantInputs() {
          var inputRow = document.getElementById('another-participant-template').cloneNode(true);
          inputRow.style.display = '';
          document.getElementById('further-participants').appendChild(inputRow);
        }
      </script>

      <div id="further-participants">
        <div id="another-participant-template" style="display:none;">
          <div class="grid one-third">
            <label for="firstnames"><?php echo __("Vorname", "hueman-7l-child"); ?>*</label>
            <input type="text" placeholder="<?php echo __('Vorname', "hueman-7l-child"); ?>" id="firstnames[]" name="firstnames[]"
              value="<?php /*echo esc_attr($firstname);*/ ?>"/>
          </div>

          <div class="grid one-third">
            <label for="lastnames"><?php echo __('Nachname', "hueman-7l-child"); ?>*</label>
            <input type="text" placeholder="<?php echo __('Nachname', "hueman-7l-child"); ?>" id="lastnames[]" name="lastnames[]"
              value="<?php /*echo esc_attr($lastname);*/ ?>"/>
          </div>

          <div class="grid one-third last">
            <label for="ages"><?php echo __("Alter", "hueman-7l-child"); ?>*</label>
            <input type="text" placeholder="<?php echo __('Alter', "hueman-7l-child"); ?>" id="ages[]" name="ages[]"
              value="<?php /*echo esc_attr($age);*/ ?>"/>
          </div>
        </div>

        <h5><?php echo __('Weitere Teilnehmer*', "hueman-7l-child"); ?></h5>
        <?php if(!empty($registration['participant_data'])) { ?>
          <?php for($i = 1; $i < count($lastnames); $i++) { ?>
            <div class="grid one-third">
              <label for="firstnames"><?php echo __("Vorname", "hueman-7l-child"); ?>*</label>
              <input type="text" placeholder="<?php echo __('Vorname', "hueman-7l-child"); ?>" id="firstnames[]" name="firstnames[]"
                value="<?php echo esc_attr($firstnames[$i]); ?>"/>
            </div>

            <div class="grid one-third">
              <label for="lastnames"><?php echo __('Nachname', "hueman-7l-child"); ?>*</label>
              <input type="text" placeholder="<?php echo __('Nachname', "hueman-7l-child"); ?>" id="lastnames[]" name="lastnames[]"
                value="<?php echo esc_attr($lastnames[$i]); ?>"/>
            </div>

            <div class="grid one-third last">
              <label for="ages"><?php echo __("Alter", "hueman-7l-child"); ?>*</label>
              <input type="text" placeholder="<?php echo __('Alter', "hueman-7l-child"); ?>" id="ages[]" name="ages[]"
                value="<?php echo esc_attr($ages[$i]); ?>"/>
            </div>
          <?php } ?>
        <?php } ?>
        <button type="button" onClick="addParticipantInputs();"><i class="fa fa-plus"></i><?php echo __("Hinzufügen", 'hueman-7l-child'); ?></button>
      </div>

      <br class="clear"/>
      <br/>


      <h5><?php echo __('Übernachtung: Raumwünsche', "hueman-7l-child"); ?></h5>
      <div class="grid one-half">
        <input type="checkbox" name="room_wish[]" id="4_Bett_Zimmer" value="4-Bett-Zimmer" <?php echo_checked_room("4-Bett-Zimmer"); ?>/>
        <label for="4_Bett_Zimmer">4-Bett-Zimmer</label>
        <br/>

        <input id='2_Bett_Zimmer' name='room_wish[]' type='checkbox' value='2-Bett-Zimmer' <?php echo_checked_room("2-Bett-Zimmer"); ?>/>
        <label for='2_Bett_Zimmer'>2-Bett-Zimmer</label>
        <br/>

        <input id='Einzelzimmer' name='room_wish[]' type='checkbox' value='Einzelzimmer' <?php echo_checked_room("Einzelzimmer"); ?>/>
        <label for='Einzelzimmer'>Einzelzimmer</label>
        <br/>

        <input id='H_tte' name='room_wish[]' type='checkbox' value='Hütte' <?php echo_checked_room("Hütte"); ?>/>
        <label for='H_tte'>Hütte</label>
      </div>

      <div class="grid one-half last">
        <input id='Eigenes_Zelt' name='room_wish[]' type='checkbox' value='Eigenes Zelt' <?php echo_checked_room("Eigenes Zelt"); ?>/>
        <label for='Eigenes_Zelt'>Eigenes Zelt</label>
        <br/>

        <input id='Eigenes_Wohnmobil_wagen' name='room_wish[]' type='checkbox' value='Eigenes Wohnmobil/-wagen' <?php echo_checked_room("Eigenes Wohnmobil/-wagen"); ?>/>
        <label for='Eigenes_Wohnmobil_wagen'>Eigenes Wohnmobil/-wagen</label>
        <br/>

        <input id='Privat_Selbstorganisiert' name='room_wish[]' type='checkbox' value='Privat / Selbstorganisiert' <?php echo_checked_room("Privat / Selbstorganisiert"); ?>/>
        <label for='Privat_Selbstorganisiert'>Privat / Selbstorganisiert</label>
      </div>
      <br class="clear"/>

      <label id='urllabel' for='url'>Hier bitte nichts reinschreiben! / Please do not enter anything here:</label>
      <input type="text" id="url" name="url"/>

      <br/>
      <br class="clear"/>
      <div class="donationx">
        <h4><?php echo __('Spenden', "hueman-7l-child"); ?></h4>
        <?php echo __("Ich möchte das Ökodorf unterstützen und zusätzlich zu meinen Seminarkosten folgenden Betrag als Spende für das neue <a href='https://siebenlinden.org/de/aktuelle-projekte/seminarzentrum/'>Seminarzentrum </a> zahlen:", 'hueman-7l-child'); ?>
        <select type="select" id="donation" name="donation">
          <option name="donate0"     <?php selected($donation, "donate0"); ?> value="donate0"><?php echo __("Ich möchte oder kann nicht spenden", 'hueman-7l-child'); ?></option>
          <option name="donate10"    <?php selected($donation, "donate10"); ?> value="donate10"><?php echo __("10€", 'hueman-7l-child'); ?></option>
          <option name="donate20"    <?php selected($donation, "donate20"); ?> value="donate20"><?php echo __("20€", 'hueman-7l-child'); ?></option>
          <option name="donate50"    <?php selected($donation, "donate50"); ?> value="donate50"><?php echo __("50€", 'hueman-7l-child'); ?></option>
          <option name="donateother" <?php selected($donation, "donateother"); ?> value="donateother"><?php echo __("anderer Betrag", 'hueman-7l-child'); ?></option>
        </select>
        <input type="text" id="donateamount" name="donateamount" size="20" placeholder="<?php echo __('... andere Zahl eingeben', 'hueman-7l-child'); ?>" value="<?php echo esc_attr($donateamount); ?>"/>
        <br/>
        <br/>
        <br class="clear"/>
      </div>
      <div class="registration-controls">
      <h4><?php echo __('Rücktrittsbedingungen', "hueman-7l-child"); ?></h4>
      <span class="cancel_conditions"><?php echo get_post_meta($post->ID, 'cancel_conditions', true); ?></span><br/>
      <br/>
      <?php echo_fa_warning_if_unposted("accept_tos"); ?>
      <input type="checkbox" id="accept_tos" name="accept_tos" <?php echo ($accept_tos ? 'checked="checked"' : '' ); ?>><?php echo __('Ich akzeptiere die Rücktrittsbedingungen und die ', "hueman-7l-child"); ?><a href="/seminare/agb/"><?php echo __('Allgemeinen Geschäftsbedingungen', "hueman-7l-child"); ?></a></input>
      <br/>
      <br/>

      <input type="hidden" name="submitted" value="1">
      <input type="submit" value="<?php echo __('Anmelden', "hueman-7l-child"); ?>"></submit>
      </div>
    </form>
   <?php } /* !$success */ else { ?>
    <div id="response"><?php echo $response; ?></div>
   <?php } /* $success */ ?>

  </div> <!-- #registration -->

