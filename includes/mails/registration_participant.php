<?php
  global $registration;
  global $event;
  global $event_uuid;
  global $event_fromdate;
  global $event_todate;
?>
  Danke für die Anmeldung für das Seminar "<?php echo the_title(); ?>" vom <?php echo $event_fromdate; ?> bis zum <?php echo $event_todate; ?> im Ökodorf Sieben Linden!

Dies ist eine automatisch generierte E-Mail.

Eine persönliche Anmeldebestätigung mit Informationen zu Anreise, Unterkunft, Bezahlung usw. folgt per E-Mail in den nächsten Tagen. Sollte innerhalb einer Woche keine Rückmeldung aus dem Bildungsreferat kommen, bitte folgende E-Mailadresse oder Telefonnummer kontaktieren:

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

<php foreach($registration["adults"] as $person) {
  #  echo $person[... ... age]
>

<?php echo $registration['firstname'].' '.$registration['lastname']; ?>


Mit folgenden Wünschen bezüglich der Unterkunft:

<?php echo implode(', ', $registration['room_wishes']); ?>


Folgende Rücktrittsbedingungen wurden akzeptiert:

<?php echo get_post_meta($post->ID, 'cancel_conditions', true); ?>


Bei Fragen kontaktiere uns gerne unter oben genannter E-Mail Adresse oder rufe uns an!

