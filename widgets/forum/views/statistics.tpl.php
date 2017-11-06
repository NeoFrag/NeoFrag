<ul class="list-group">
	<li class="list-group-item"><?php echo icon('fa-clipboard').' '.$this->lang('<b>%d</b> sujet créé|<b>%d</b> sujets créés', $data['topics'], $data['topics']) ?></li>
	<li class="list-group-item"><?php echo icon('fa-comments-o').' '.$this->lang('<b>%d</b> message posté|<b>%d</b> messages postés', $data['messages'], $data['messages']) ?></li>
	<li class="list-group-item"><?php echo icon('fa-flag').' '.$this->lang('<b>%d</b> annonce|<b>%d</b> annonces', $data['announces'], $data['announces']) ?></li>
	<li class="list-group-item"><?php echo icon('fa-users').' '.$this->lang('<b>%d</b> participant|<b>%d</b> participants', $data['users'], $data['users']) ?></li>
</ul>
