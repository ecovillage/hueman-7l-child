<?php
  global $registration;
  global $event;
  global $event_uuid;
  global $event_fromdate;
  global $event_todate;
?>
Anmeldung für "<?php echo $registration['event_name']; ?>" vom <?php echo $event_fromdate; ?> bis zum <?php echo $event_todate; ?>

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
// Evaluate the values entered in donation related fields.
// Mail will be sent to the host.
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

Spende unklar:
Der Seminargast hat eine Spende über <?php echo $valDonation;?>.- angewählt und einen Betrag von <?php echo $registration['donateamount']; ?>.- Euro eingegeben.
<?php } else if (preg_match ("/^([0-9]+)$/", $valDonation) && $valDonation == 0 && preg_match ("/^([0-9]+)$/", $registration['donateamount']) && $registration['donateamount'] > 0){
  // Nothing selected in left select field, but value found in right free text field. Needs check. ?>

Spende unsicher:
Der Seminargast hat keine Spende angewählt und einen Betrag von <?php echo $registration['donateamount']; ?>.- Euro eingegeben.
<?php } else if (preg_match ("/^([0-9]+)$/", $valDonation) && $valDonation > 0) {
  // Proper donation was selected ?>

Spende:
Der Seminargast möchte <?php echo $valDonation;?>.- Euro spenden.
<?php } else{
  // No donation ?>

Keine Spende.
<?php } ?>

