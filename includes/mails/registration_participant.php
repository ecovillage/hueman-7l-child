<?php
  global $registration;
  global $event;
  global $event_uuid;
  global $event_fromdate;
  global $event_todate;
?>
  Danke für die Anmeldung für das Seminar "<?php echo the_title(); ?>" vom <?php echo $event_fromdate; ?> bis zum <?php echo $event_todate; ?> im Ökodorf Sieben Linden!

Dies ist eine automatisch generierte E-Mail.

Eine persönliche Antwort aus dem Bildungsreferat mit Informationen zu Anreise, Unterkunft, Bezahlung usw. folgt per E-Mail in den nächsten Tagen. Sollte innerhalb einer Woche keine Rückmeldung aus dem Bildungsreferat kommen, bitte folgende E-Mailadresse oder Telefonnummer kontaktieren:

E-Mail: bildungsreferat@siebenlinden.de

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

<?php if (!empty($registration['donation']) || !empty($registration['donateamount'])) { ?>

  Du hast angegeben, Dich am Bau des Seminarzentrums mit einer Spende beteiligen zu wollen, das freut uns sehr!

<?php } ?>


Bei Fragen kontaktiere uns gerne unter oben genannter E-Mail Adresse oder rufe uns an!

