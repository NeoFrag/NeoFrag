<p><?php echo $this->lang('Il y a %d utilisateur sur le forum|Il y a %d utilisateurs sur le forum', $count = ($n = count($users)) + $visitors, $count) ?></p>
<?php echo implode(', ', array_map(function($a){ return $this->user->link($a['user_id'], $a['username']); }, $users)).' '.($n ? $this->lang('et') : '').' '.$this->lang('{0}aucun visiteur|{1}%d visiteur|%d visiteurs', $visitors, $visitors) ?>
