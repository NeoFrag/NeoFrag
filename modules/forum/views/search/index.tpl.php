<p class="pull-right"><?php echo icon('fa-comments').' <a href="'.url('forum/'.$forum_id.'/'.url_title($title)).'">'.$title.'</a> '.icon('fa-user').' '.($user_id ? $this->user->link($user_id, $username) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('fa-clock-o').' '.time_span($date) ?></p>
<big><b><a href="<?php echo url('forum/topic/'.$topic_id.'/'.url_title($topic_title).($count_messages > $this->config->forum_messages_per_page ? '/page/'.ceil($count_messages / $this->config->forum_messages_per_page) : '').'#'.$message_id) ?>"><?php echo $topic_title ?></a></b></big>
<br />
<br />
<p><?php echo $message ?></p>
