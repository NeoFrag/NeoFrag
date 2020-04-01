<ul class="forum-tree list-unstyled mb-0">
	<?php
		foreach ($categories as $category_id => $category)
		{
			echo '	<li><h5>'.icon('fas fa-bars').' '.$category['title'].'</h5></li>
					<li>
						<ul class="forum-tree forum-tree-forums list-unstyled ml-4">';

			foreach ($category['forums'] as $forum_id => $forum)
			{
				echo '	<li><h6 class="m-0"><label><input type="radio" name="forum_id" value="'.$forum_id.'"'.($forum_id == $current ? ' checked="checked"' : '').' />'.icon('far fa-comments').' '.$forum['title'].'</label></h6></li>';

				if ($forum['subforums'])
				{
					echo '	<li>
								<ul class="forum-tree forum-tree-subforums list-unstyled ml-4">';

					foreach ($forum['subforums'] as $subforum_id => $subforum)
					{
						echo '	<li><label><input type="radio" name="forum_id" value="'.$subforum_id.'"'.($subforum_id == $current ? ' checked="checked"' : '').' />'.icon('far fa-comments').' '.$subforum.'</label></li>';
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
