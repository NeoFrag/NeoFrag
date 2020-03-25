<p class="float-right"><?php echo icon('far fa-bookmark').' <a href="'.url('news/category/'.$category_id.'/'.url_title($category)).'">'.$category.'</a> '.icon('fas fa-user').' '.($user_id ? $this->user->link($user_id, $username) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('far fa-clock').' '.time_span($date) ?></p>
<big><b><a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>"><?php echo $title ?></a></b></big>
<br />
<br />
<p><?php echo $introduction ?></p>
