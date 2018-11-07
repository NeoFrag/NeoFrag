<ul class="list-group list-group-flush">
	<li class="list-group-item"><?php echo icon('fa-clipboard').' '.$this->lang('%d sujet créé|%d sujets créés', $topics, $topics) ?></li>
	<li class="list-group-item"><?php echo icon('fa-comments-o').' '.$this->lang('%d message posté|%d messages postés', $messages, $messages) ?></li>
	<li class="list-group-item"><?php echo icon('fa-flag').' '.$this->lang('%d annonce|%d annonces', $announces, $announces) ?></li>
	<li class="list-group-item"><?php echo icon('fa-users').' '.$this->lang('%d participant|%d participants', $users, $users) ?></li>
</ul>
