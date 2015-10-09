<ul class="list-group">
	<li class="list-group-item"><?php echo icon('fa-clipboard').' '.i18n('forum_topics', $data['topics'], $data['topics']); ?></li>
	<li class="list-group-item"><?php echo icon('fa-comments-o').' '.i18n('forum_messages', $data['messages'], $data['messages']); ?></li>
	<li class="list-group-item"><?php echo icon('fa-flag').' '.i18n('forum_announces', $data['announces'], $data['announces']); ?></li>
	<li class="list-group-item"><?php echo icon('fa-users').' '.i18n('contributors', $data['users'], $data['users']); ?></li>
</ul>