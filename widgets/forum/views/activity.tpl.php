<p><?php echo $this->lang('there_are', $count = ($n = count($users)) + $visitors, $count) ?></p>
<?php echo implode(', ', array_map(function($a){ return $this->user->link($a['user_id'], $a['username']); }, $users)).' '.($n ? $this->lang('and') : '').' '.$this->lang('guests', $visitors, $visitors) ?>
