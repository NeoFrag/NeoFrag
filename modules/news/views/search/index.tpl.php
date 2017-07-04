<p class="pull-right"><?php echo icon('fa-bookmark-o').' <a href="'.url('news/category/'.$category_id.'/'.url_title($category)).'">'.$category.'</a> '.icon('fa-user').' '.($user_id ? $this->user->link($user_id, $username) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('fa-clock-o').' '.time_span($date) ?></p>
<big><b><a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>"><?php echo $title ?></a></b></big>
<br />
<br />
<p><?php echo $introduction ?></p>
