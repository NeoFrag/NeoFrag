<ul class="list-group">
	<li class="list-group-item"><?php echo icon('fa-clipboard').' '.$this->lang('forum_topics', $data['topics'], $data['topics']); ?></li>
	<li class="list-group-item"><?php echo icon('fa-comments-o').' '.$this->lang('forum_messages', $data['messages'], $data['messages']); ?></li>
	<li class="list-group-item"><?php echo icon('fa-flag').' '.$this->lang('forum_announces', $data['announces'], $data['announces']); ?></li>
	<li class="list-group-item"><?php echo icon('fa-users').' '.$this->lang('contributors', $data['users'], $data['users']); ?></li>
</ul>