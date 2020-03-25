<?php if ($type && !$this->url->admin): ?>
<ul class="list-inline float-right m-0">
	<li><?php echo $this->label($type['title'], $type['icon'], $type['color']) ?></li>
</ul>
<?php endif ?>
<ul class="list-inline<?php echo $this->url->admin ? '' : ' m-0' ?>">
	<li class="list-inline-item"><?php echo icon('fas fa-sliders-h') ?></li>
	<li class="list-inline-item"><a href="<?php echo url(($this->url->admin) ? 'admin/events' : 'events') ?>"><?php echo ($this->url->request == 'events' || $this->url->request == 'admin/events') ? '<b>Tous</b>' : 'Tous' ?></a></li>
	<li class="list-inline-item"><a href="<?php echo url(($this->url->admin) ? 'admin/events/standards' : 'events/standards') ?>"><?php echo ($this->url->request == 'events/standards' || $this->url->request == 'admin/events/standards') ? '<b>Standards</b>' : 'Standards' ?></a></li>
	<li class="list-inline-item"><a href="<?php echo url(($this->url->admin) ? 'admin/events/matches' : 'events/matches') ?>"><?php echo ($this->url->request == 'events/matches' || $this->url->request == 'events/matches/list' || $this->url->request == 'admin/events/matches') ? '<b>Résultats</b>' : 'Résultats' ?></a></li>
	<li class="list-inline-item"><a href="<?php echo url(($this->url->admin) ? 'admin/events/upcoming' : 'events/upcoming') ?>"><?php echo ($this->url->request == 'events/upcoming' || $this->url->request == 'events/upcoming/list' || $this->url->request == 'admin/events/upcoming') ? '<b>Matchs à jouer</b>' : 'Matchs à jouer' ?></a></li>
</ul>
