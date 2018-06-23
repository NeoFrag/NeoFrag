<?php if (0 && $messages = $this->user->get_messages()): ?>
<a href="<?php echo url('user/messages') ?>" class="btn btn-info btn-block btn-lg">Vous avez <?php echo $messages > 0 ? $messages.' messages non lus !' : '1 message non lu !' ?></a>
<?php else: ?>
Aucun nouveau message...
<?php endif ?>
