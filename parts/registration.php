<?php

$response = __("");
$success  = false;

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

$missing_info  = __("Please provide all information");
$email_invalid = __("Please provide a valid email adress");
$message_sent  = __("Message was sent..... if ... ");

// User provided content
$firstname     = $_POST['firstname'];
$lastname      = $_POST['lastname'];
$street_and_no = $_POST['street_and_no'];
$zip           = $_POST['zip'];
$city          = $_POST['city'];
$country       = $_POST['country'];
$email         = $_POST['email'];
$phone         = $_POST['phone'];
$mobile        = $_POST['mobile'];
$comment       = $_POST['comment'];

#$registration[] = array(
$registration = array(
  # 'uuid'     => ran(),
  'firstname' => $firstname,
  'lastname'  => $lastname,
  'place'     => $city,
  'zip'       => $zip,
  'cellphone' => $mobile,
  'telephone' => $phone,
  'email'     => $email,
  'address'   => $street_and_no,
  'room_wishes' => array(),
  'comments'  => $comment,
  'country'   => $country,
  'l_seminar' => '1'
);
# Missing fields: rooms, tos, donation, further participants

if (false) {
  // validate (mail - adress, name, tos
}

if ($_POST['submitted']) {
  file_put_contents("registration.txt", json_encode($registration));
  // mail to participant, mail to host
}

registration_form_error("Debug: ".var_export($registration));
?>

  <div id="registration">
    <div id="response"><?php echo $response; ?></div>

    <?php if (!$success) { ?>

    <hr/>
    <h2>__("Anmeldung")</h2>

    <span style="color:red;">
      <?php echo __("Bei technischen Problemen benuzte bitte unser "); ?>
      <a href="http://seminare.siebenlinden.de/seminar/1ee1ff4c-6a9d-11e6-9c78-78e7d1f4cba4"> Anmeldungen über alte Webseite hier möglich.</a>
    </span>
    <form id="registration_form" action="<?php the_permalink(); ?>" charset="UTF-8" method="POST">
      <input type="hidden" name="seminar_id" value="1ee1ff4c-6a9d-11e6-9c78-78e7d1f4cba4"/>

      <div class="grid one-half">
      <label for="firstname"><?php echo __("Vorname"); ?></label>
      <input type="text" placeholder="<?php echo __('Vorname'); ?>" id="firstname" name="firstname"
          value="<?php echo esc_attr($firstname); ?>"/>

          <label for="lastname"><?php echo __('Nachname'); ?></label>
          <input type="text" placeholder="<?php echo __('Nachname'); ?>" id="lastname" name="lastname"
          value="<?php echo esc_attr($lastname); ?>"/>

          <label for="street_and_no"><?php echo __('Straße und Hausnummer'); ?></label>
          <input type="text" placeholder="<?php echo __('Straße und Hausnummer'); ?>" id="street_and_no" name="street_and_no"
          value="<?php echo esc_attr($street_and_no); ?>"/>

          <label for="zip"><?php echo __('PLZ'); ?></label>
          <input type="text" placeholder="<?php echo __('PLZ'); ?>" id="zip" name="zip"
          value="<?php echo esc_attr($zip); ?>"/>

          <label for="city"><?php echo __('Ort'); ?></label>
          <input type="text" placeholder="<?php echo __('Ort'); ?>" id="city" name="city"
          value="<?php echo esc_attr($city); ?>"/>

          <label for="land"><?php echo __('Land'); ?></label>
          <input type="text" placeholder="<?php echo __('Land'); ?>" id="country" name="country"
          value="<?php echo esc_attr($country); ?>"/>
      </div>

      <div class="grid one-half last">
      <label for="email"><?php echo __('E-Mail'); ?></label>
      <input type="text" placeholder="<?php echo __('email'); ?>" id="email" name="email"
          value="<?php echo esc_attr($email); ?>"/>

          <label for="phone"><?php echo __('Telefonnummer'); ?></label>
        <input type="text" placeholder="0123 45678" id="phone" name="phone"
          value="<?php echo esc_attr($phone); ?>"/>

          <label for="mobile"><?php echo __('Handy-Nummer'); ?></label>
        <input type="text" placeholder="0123 45678" id="mobile" name="mobile"
          value="<?php echo esc_attr($mobile); ?>"/>

          <label for="comment"><?php echo __('Bemerkung'); ?></label>
        <textarea placeholder="..." id="comment" name="comment" rows="7"></textarea>
      </div>
      <br class="clear"/>

                    <br/>
                    <h5><?php echo __('Übernachtung: Raumwünsche'); ?></h5>
                    <div class="grid one-half last">
                      <input type="checkbox" name="room_wish[]" id="4_Bett_Zimmer" value="4-Bett-Zimmer"/>
                      <label for="4_Bett_Zimmer">4-Bett-Zimmer</label>
                      <br/>

                      <input id='2_Bett_Zimmer' name='room_wish[]' type='checkbox' value='2-Bett-Zimmer'/>
                      <label for='2_Bett_Zimmer'>2-Bett-Zimmer</label>
                      <br/>

                      <input id='Einzelzimmer' name='room_wish[]' type='checkbox' value='Einzelzimmer'/>
                      <label for='Einzelzimmer'>Einzelzimmer</label>
                      <br/>

                      <input id='H_tte' name='room_wish[]' type='checkbox' value='Hütte'/>
                      <label for='H_tte'>Hütte</label>
                    </div>

                    <div class="grid one-half last">
                      <input id='Eigenes_Zelt' name='room_wish[]' type='checkbox' value='Eigenes Zelt'/>
                      <label for='Eigenes_Zelt'>Eigenes Zelt</label>
                      <br/>

                      <input id='Eigenes_Wohnmobil_wagen' name='room_wish[]' type='checkbox' value='Eigenes Wohnmobil/-wagen'/>
                      <label for='Eigenes_Wohnmobil_wagen'>Eigenes Wohnmobil/-wagen</label>
                      <br/>

                      <input id='Privat_Selbstorganisiert' name='room_wish[]' type='checkbox' value='Privat / Selbstorganisiert'/>
                      <label for='Privat_Selbstorganisiert'>Privat / Selbstorganisiert</label>
                    </div>
                    <br class="clear"/>


                    <br/>
                    <br class="clear"/>
                    <br/>
                    <div class="registration-controls">
                    <h4><?php echo __('Rücktrittsbedingungen'); ?></h4>
                    <span class="cancel_conditions">Bei Rücktritt bis 28 Tage vor Seminarbeginn: keine Rücktrittsgebühr. Bei Rücktritt 28-14 Tage vor Seminarbeginn: 50 Eur Rücktrittsgebühr pro Person. Bei Rücktritt ab dem 14. Tag vor Seminarbeginn ist der volle Teilnahmebeitrag inkl. Unterkunftskosten zu zahlen. Bei Rücktritt ab 7 Tage vor Seminarbeginn oder Nichtteilnahme ohne Abmeldung ist der volle Teilnahmebeitrag inkl. Unterkunfts- und Verpflegungskosten zu zahlen.</span><br/>
                    <br/>
                    <input type="checkbox" id="accept_terms" name="registration[accept_terms]">Ich akzeptiere die Rücktrittsbedingungen und die <a href="/seminare/agb/">Allgemeinen Geschäftsbedingungen</a></input>
                    <br/>
                    <br/>

                    <input type="hidden" name="submitted" value="1">
                    <input type="submit" value="<?php echo __('Anmelden'); ?>"></submit>
                    </div>
                  </form>
   <?php } /* !$success */ ?>

  </div> <!-- #registration -->

