<p class="pull-right"><?php echo icon('fa-bookmark-o').' <a href="'.url('news/category/'.$data['category_id'].'/'.url_title($data['category'])).'">'.$data['category'].'</a> '.icon('fa-user').' '.($data['user_id'] ? $this->user->link($data['user_id'], $data['username']) : '<i>'.$this->lang('guest').'</i>').' '.icon('fa-clock-o').' '.time_span($data['date']); ?></p>
<big><b><a href="<?php echo url('news/'.$data['news_id'].'/'.url_title($data['title'])); ?>"><?php echo $data['title']; ?></a></b></big>
<br />
<br />
<p><?php echo $data['introduction']; ?></p>