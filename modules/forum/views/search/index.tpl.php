<p class="float-right"><?php echo icon('fas fa-comments').' <a href="'.url('forum/'.$forum_id.'/'.url_title($title)).'">'.$title.'</a> '.icon('fas fa-user').' '.($user_id ? $this->user->link($user_id, $username) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('far fa-clock').' '.time_span($date) ?></p>
<big><b><a href="<?php echo url('forum/topic/'.$topic_id.'/'.url_title($topic_title).($count_messages > $this->config->forum_messages_per_page ? '/page/'.ceil($count_messages / $this->config->forum_messages_per_page) : '').'#'.$message_id) ?>"><?php echo $topic_title ?></a></b></big>
<br />
<br />
<p><?php echo $message ?></p>
