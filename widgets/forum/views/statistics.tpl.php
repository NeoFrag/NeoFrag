<ul class="list-group">
	<li class="list-group-item"><?php echo icon('fa-clipboard'); ?> <b><?php echo $data['topics']; ?></b> <?php echo $data['topics'] > 1 ? 'sujets créés' : 'sujet créé'; ?></li>
	<li class="list-group-item"><?php echo icon('fa-comments-o'); ?> <b><?php echo $data['messages']; ?></b> <?php echo $data['messages'] > 1 ? 'messages postés' : 'message posté'; ?></li>
	<li class="list-group-item"><?php echo icon('fa-flag'); ?> <b><?php echo $data['announces']; ?></b> <?php echo $data['announces'] > 1 ? 'annonces' : 'annonce'; ?></li>
	<li class="list-group-item"><?php echo icon('fa-users'); ?> <b><?php echo $data['users']; ?></b> <?php echo $data['users'] > 1 ? 'participants' : 'participant'; ?></li>
</ul>