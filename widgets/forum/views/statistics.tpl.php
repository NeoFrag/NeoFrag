<ul class="list-group">
	<li class="list-group-item"><i class="fa fa-clipboard"></i> <b>{topics}</b> <?php echo $data['topics'] > 1 ? 'sujets créés' : 'sujet créé'; ?></li>
	<li class="list-group-item"><i class="fa fa-comments-o"></i> <b>{messages}</b> <?php echo $data['messages'] > 1 ? 'messages postés' : 'message posté'; ?></li>
	<li class="list-group-item"><i class="fa fa-flag"></i> <b>{announces}</b> <?php echo $data['announces'] > 1 ? 'annonces' : 'annonce'; ?></li>
	<li class="list-group-item"><i class="fa fa-users"></i> <b>{users}</b> <?php echo $data['users'] > 1 ? 'participants' : 'participant'; ?></li>
</ul>