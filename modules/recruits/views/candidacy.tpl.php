Envoyée le <b><?php echo timetostr('%e %b %Y', $date) ?></b>, pour<?php echo $team_id ? ' rejoindre l\'équipe <b>'.$team_name.'</b> au poste de <b>'.$role.'</b>' : ' le poste de <b>'.$role.'</b>' ?>
<hr />
<h4>Présentation</h4>
<?php echo $presentation ? $presentation : 'Non renseigné.' ?>
<hr />
<h4>Motivations</h4>
<?php echo $motivations ? $motivations : 'Non renseigné.' ?>
<hr />
<h4>Expériences</h4>
<?php echo $experiences ? $experiences : 'Non renseigné.' ?>
