<?php if ($event_needs_registration && $event_is_future) { ?>
<div class="event-subsection-nav">
  <div class="grid one-fifth">
    <a href="#description"><?php echo _('Beschreibung', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fifth">
    <a href="#referees"><?php echo __('Referent*', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fifth">
    <a href="#informations"><?php echo __('Informationen', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fifth">
    <a href="#registration"><?php echo __('Anmeldung', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fifth last">
    <a href="#question"><?php echo __('Frage stellen', 'hueman-7l-child'); ?></a>
  </div>
</div>
<?php } else { ?>
<div class="event-subsection-nav">
  <div class="grid one-fourth">
    <a href="#description"><?php echo __('Beschreibung', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fourth">
    <a href="#referees"><?php echo __('Referent*', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fourth">
    <a href="#informations"><?php echo __('Informationen', 'hueman-7l-child'); ?></a>
  </div>
  <div class="grid one-fourth last">
    <a href="#question"><?php echo __('Frage stellen', 'hueman-7l-child'); ?></a>
  </div>
</div>
<?php } ?>
<div class="clear"></div>

