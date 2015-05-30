<?php if ($data['image_id']): ?>
<a href="{base_url}teams/{team_id}/{name}.html"><img class="img-responsive" src="{image {image_id}}" alt="" /></a>
<?php endif; ?>
<div class="panel-body">
	<?php if ($data['description']): ?>
	<h3>Présentation</h3>
	{description}
	<?php endif; ?>
	<h3>Nos joueurs</h3>
	{users}
</div>