<?php
  global $registration;
  global $event;
  global $event_uuid;
  global $event_fromdate;
  global $event_todate;
?>
Anmeldung für "<?php echo the_title(); ?>" vom <?php echo $event_fromdate; ?> bis zum <?php echo $event_todate; ?>

Anschrift:

<?php echo $registration['firstname'].' '.$registration['lastname']; ?> 
<?php echo $registration['address']; ?> 
<?php echo $registration['zip'].' '.$registration['place']; ?> 
<?php echo $registration['country']; ?> 

e-Mail:  <?php echo $registration['email']; ?> 
Telefon: <?php echo $registration['telephone']; ?> 
Handy:   <?php echo $registration['cellphone']; ?> 

<?php $ages = $registration["participant_data"]; ?>

Personen:
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



Wünsche zur Unterkunft:

<?php echo implode(', ', $registration['room_wishes']); ?>


Bemerkungen:

<?php echo $registration['comments']; ?>

