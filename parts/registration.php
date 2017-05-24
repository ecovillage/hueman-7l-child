<?php

$response = "";
$success  = false;

$msg_missing_info   = __("Please provide all information", "hueman-7l-child");
$msg_email_invalid  = __("Please provide a valid email adress", "hueman-7l-child");
$msg_message_sent   = __("Message was sent..... if ... ", "hueman-7l-child");
$msg_message_not_sent = __("Message was not sent..... if ... ", "hueman-7l-child");
$msg_need_tos       = __("You need to accept the tos and cancellation conditions", "hueman-7l-child");
$msg_registered     = __("Registration received. You should receive a mail within the next minutes. If not ...", "hueman-7l-child");
$msg_technical_error= __("There was a real technical error with your registration. Please contact ....", "hueman-7l-child");

function registration_form_success($message) {
  global $response;
  $response = '<div class="success">'.$message.'</div>';
  global $success;
  $success  = true;
};

function registration_form_error($message) {
  global $response;
  $response = '<div class="error">'.$message.'</div>';
};

function send_mail_to_host($registration) {
  $to = 'bildungsreferat@siebenlinden.de';
  $subject = 'Registration';
  $headers = 'From: '. $registration['email'] . "\r\n" .
    'Reply-To: ' . $registration['email'] . "\r\n";
  // Need a kind of template here
  $sent = wp_mail($to, $subject, strip_tags($registration['comments']), $headers);
  #if($sent) {
  #  registration_form_success($msg_message_sent); //message sent!
  #}
  #else {
  #  registration_form_error($msg_message_not_sent); //message wasn't sent
  #}
  return $sent;
}

function send_mail_to_participant($registration) {
  $to = $registration['email'];
  $subject = 'Registration';
  $headers = 'From: seminar-registrierung@siebenlinden.org'. "\r\n" .
    'Reply-To: bildungsreferat@siebenlinden.de' . "\r\n";
  $sent = wp_mail($to, $subject, strip_tags($registration['comments']), $headers);
  return $sent;
}

// Access field in $_POST if it is set, return false otherwise.
function post_index_or_null($key) {
  return (isset($_POST[$key])) ? $_POST[$key] : false;
};

function echo_fa_warning_if_unposted($key) {
  if (!empty($_POST) && (!isset($_POST[$key]) || empty($_POST[$key]))) {
    echo '<i class="fa fa-warning"></i>';
  }
}

function echo_checked_room($roomname) {
  echo (isset($_POST['room_wish']) && in_array($roomname, $_POST['room_wish']) ? 'checked="checked"' : '');
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
$comment       = post_index_or_null('comment');
$accept_tos    = post_index_or_null('accept_tos');
$submitted     = post_index_or_null('submitted');
$rooms         = post_index_or_null('room_wish');

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
);
# Missing fields: uuid, rooms, donation, further participants

if (true) {
  if (!$submitted) {
    // Pseudo-spam protection
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
    $filename = "registration.txt";
    if (!file_put_contents($filename, json_encode($registration))) {
      registration_form_error($msg_technical_error);
    }
    elseif (send_mail_to_participant($registration) && send_mail_to_host($registration)) {
      #registration_form_success("validation passed");
      registration_form_success($msg_registered);
    } else {
      registration_form_error($msg_message_not_sent);
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

    <span style="color:red;">
      <?php echo __("Bei technischen Problemen benutze bitte unser ", "hueman-7l-child"); ?>
<a href="http://seminare.siebenlinden.de/seminar/<?php echo $event_uuid; ?>"><?php echo __('Anmeldesystem der alten Webseite.', "hueman-7l-child"); ?></a>
    </span>
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

        <label for="comment"><?php echo __('Bemerkung', "hueman-7l-child"); ?></label>
        <textarea placeholder="..." id="comment" name="comment" rows="7"></textarea>
      </div>

      <br class="clear"/>
      <br/>

      <h5><?php echo __('Übernachtung: Raumwünsche', "hueman-7l-child"); ?></h5>
      <div class="grid one-half last">
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


      <br/>
      <br class="clear"/>
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

