<?php
  global $registration;
  global $event;
  global $event_uuid;
  global $event_fromdate;
  global $event_todate;
?>
  Danke für die Anmeldung für das Seminar "<?php echo $registration['event_name']; ?>" vom <?php echo $event_fromdate; ?> bis zum <?php echo $event_todate; ?> im Ökodorf Sieben Linden!

Dies ist eine automatisch generierte E-Mail.

<?php
  $notice = get_option('h7lc_host_mail_temporary_notice_field');
  if (!empty($notice)) {
    echo "Folgende aktuelle Hinweise liegen vor:\n";
    echo $notice;
    echo "\n";
  }
?>

Eine persönliche Antwort aus dem Bildungsreferat mit Informationen zu Anreise, Unterkunft, Bezahlung usw. folgt per E-Mail in den nächsten Tagen. Sollte innerhalb einer Woche keine Rückmeldung aus dem Bildungsreferat kommen, bitte folgende E-Mailadresse oder Telefonnummer kontaktieren:

E-Mail: bildungsreferat@siebenlinden.org

Tel. 039000-51236


Wir haben folgende Daten erhalten:


Anschrift:

<?php echo $registration['firstname'].' '.$registration['lastname']; ?> 
<?php echo $registration['address']; ?> 
<?php echo $registration['zip'].' '.$registration['place']; ?> 
<?php echo $registration['country']; ?> 

e-Mail:  <?php echo $registration['email']; ?> 
Telefon: <?php echo $registration['telephone']; ?> 
Handy:   <?php echo $registration['cellphone']; ?> 


Kommentar:
<?php echo $registration['comments']; ?>


Die Anmeldung wurde für folgende Personen vorgenommen:
<?php $ages = $registration["participant_data"]; ?>

<?php foreach($registration["adults"] as $person) {
  echo $person." (Alter: ".$ages[$person].")\n";
}
?>
<?php foreach($registration["youth"] as $person) {
  echo $person." (Alter: ".$ages[$person].")\n";
}
?>
<?php foreach($registration["children"] as $person) {
  echo $person." (Alter: ".$ages[$person].")\n";
}
?>


Mit folgenden Wünschen bezüglich der Unterkunft:

<?php if (!empty($registration['room_wishes'])) {
  echo implode(', ', $registration['room_wishes']);
} else {
  echo "(Keine)";
}
?>


Folgende Rücktrittsbedingungen wurden akzeptiert:

<?php echo get_post_meta($post->ID, 'cancel_conditions', true); ?>

<?php
 // Evaluation of donation-related values entered by user.
 // Mail will be sent to user (guest).
 switch ($registration['donation']) {
   case "donate10":
     $valDonation = 10;
     break;
   case "donate20":
     $valDonation = 20;
     break;
   case "donate50":
     $valDonation = 50;
     break;
   case "donateother":
     $valDonation = $registration['donateamount'];
     break;
   case "donate0":
   default:
     $valDonation = 0;
 }// end switch
 if (preg_match ("/^([0-9]+)$/", $valDonation) && $valDonation > 0 &&
    preg_match ("/^([0-9]+)$/", $registration['donateamount']) && $registration['donateamount'] > 0 &&
    $valDonation != $registration['donateamount']){
  // Select value (left) and entered value (free text, right) do not match ?>

Du hast angegeben, Dich am Bau des Seminarzentrums mit einer Spende über  <?php echo $valDonation;?>.- oder <?php echo $registration['donateamount']; ?>.- Euro beteiligen zu wollen, das freut uns sehr!
<?php } else if (preg_match ("/^([0-9]+)$/", $valDonation) && $valDonation == 0 && preg_match ("/^([0-9]+)$/", $registration['donateamount']) && $registration['donateamount'] > 0){
  // Nothing selected in left select field, but value found in right free text field. Needs check. ?>

Du hast angegeben, Dich am Bau des Seminarzentrums mit einer Spende über <?php echo $registration['donateamount']; ?>.- Euro beteiligen zu wollen, das freut uns sehr!
<?php } else if (preg_match ("/^([0-9]+)$/", $valDonation) && $valDonation > 0) {
  // Proper donation was selected ?>

Du hast angegeben, Dich am Bau des Seminarzentrums mit einer Spende über <?php echo $valDonation;?>.- Euro beteiligen zu wollen, das freut uns sehr!
<?php } ?>

Bei Fragen kontaktiere uns gerne unter oben genannter E-Mail Adresse oder rufe uns an!

