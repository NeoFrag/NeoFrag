<p class="pull-right"><?php echo icon('fa-comments').' <a href="'.url('forum/'.$data['forum_id'].'/'.url_title($data['title'])).'">'.$data['title'].'</a> '.icon('fa-user').' '.($data['user_id'] ? $this->user->link($data['user_id'], $data['username']) : '<i>'.$this->lang('guest').'</i>').' '.icon('fa-clock-o').' '.time_span($data['date']); ?></p>
<big><b><a href="<?php echo url('forum/topic/'.$data['topic_id'].'/'.url_title($data['topic_title']).($data['count_messages'] > $this->config->forum_messages_per_page ? '/page/'.ceil($data['count_messages'] / $this->config->forum_messages_per_page) : '').'#'.$data['message_id']); ?>"><?php echo $data['topic_title']; ?></a></b></big>
<br />
<br />
<p><?php echo $data['message']; ?></p>