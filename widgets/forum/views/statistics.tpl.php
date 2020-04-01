<ul class="list-group list-group-flush">
	<li class="list-group-item"><?php echo icon('far fa-clipboard').' '.$this->lang('%d sujet créé|%d sujets créés', $topics, $topics) ?></li>
	<li class="list-group-item"><?php echo icon('far fa-comments').' '.$this->lang('%d message posté|%d messages postés', $messages, $messages) ?></li>
	<li class="list-group-item"><?php echo icon('fas fa-flag').' '.$this->lang('%d annonce|%d annonces', $announces, $announces) ?></li>
	<li class="list-group-item"><?php echo icon('fas fa-users').' '.$this->lang('%d participant|%d participants', $users, $users) ?></li>
</ul>
