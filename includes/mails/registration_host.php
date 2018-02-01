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

<?php if (!empty($registration['room_wishes'])) {
  echo implode(', ', $registration['room_wishes']);
} else {
  echo "(Keine)";
}
?>


Bemerkungen:

<?php echo $registration['comments']; ?>
<?php
  $filename = "registrations/comment-".rand()."-".rand().".txt";
  $write_result = file_put_contents($filename, $registration['comments']);
?>

<?php if ((!empty($registration['donation']) && $registration['donation'] != 'donate0') || !empty($registration['donateamount'])) { ?>
  Spende:
  
  Ja (<?php echo $registration['donation'].' / '.$registration['donateamount']; ?>)!
<?php } ?>
