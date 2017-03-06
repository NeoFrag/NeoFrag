<div class="modal fade" id="modal-topic-move" tabindex="-1" role="dialog" aria-labelledby="modal-title-topic-move" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="<?php echo url('forum/topic/move/'.$data['topic_id'].'/'.url_title($data['title'])); ?>" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $this->lang('close'); ?>"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal-title-topic-move"><?php echo $this->lang('select_forum'); ?></h4>
				</div>
				<div class="modal-body">
					<ul class="forum-tree">
						<?php
							foreach ($data['categories'] as $category_id => $category)
							{
								echo '	<li><h3 class="no-margin">'.icon('fa-navicon').' '.$category['title'].'</h3></li>
										<li>
											<ul class="forum-tree forum-tree-forums">';

								foreach ($category['forums'] as $forum_id => $forum)
								{
									echo '	<li><h4 class="no-margin"><label><input type="radio" name="'.($token = $this->form->token('3a27fa5555e6f34491793733f32169db')).'[forum_id]" value="'.$forum_id.'"'.($forum_id == $data['forum_id'] ? ' checked="checked"' : '').' />'.icon('fa-comments-o').' '.$forum['title'].'</label></h4></li>';
									
									if ($forum['subforums'])
									{
										echo '	<li>
													<ul class="forum-tree forum-tree-subforums">';
										
										foreach ($forum['subforums'] as $subforum_id => $subforum)
										{
											echo '	<li><label><input type="radio" name="'.$token.'[forum_id]" value="'.$subforum_id.'"'.($subforum_id == $data['forum_id'] ? ' checked="checked"' : '').' />'.icon('fa-comments-o').' '.$subforum.'</label></li>';
										}
										
										echo '		</ul>
												</li>';
									}
								}
								
								echo '		</ul>
										</li>';
							}
						?>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang('cancel'); ?></button>
					<button class="btn btn-info"><?php echo $this->lang('continue'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>