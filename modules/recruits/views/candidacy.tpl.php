Envoyée le <b><?php echo timetostr('%e %b %Y', $data['date']); ?></b>, pour<?php echo $data['team_id'] ? ' rejoindre l\'équipe <b>'.$data['team_name'].'</b> au poste de <b>'.$data['role'].'</b>' : ' le poste de <b>'.$data['role'].'</b>'; ?>
<hr />
<h4>Présentation</h4>
<?php echo $data['presentation'] ? $data['presentation'] : 'Non renseigné.'; ?>
<hr />
<h4>Motivations</h4>
<?php echo $data['motivations'] ? $data['motivations'] : 'Non renseigné.'; ?>
<hr />
<h4>Expériences</h4>
<?php echo $data['experiences'] ? $data['experiences'] : 'Non renseigné.'; ?>