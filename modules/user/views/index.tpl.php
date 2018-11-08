<?php if ($unreads = $this->model('messages')->get_messages_unreads()): ?>
<a href="<?php echo url('user/messages') ?>" class="btn btn-primary btn-block">Vous avez <?php echo $unreads > 0 ? $unreads.' messages non lus !' : '1 message non lu !' ?></a>
<?php else: ?>
Aucun nouveau message...
<?php endif ?>
