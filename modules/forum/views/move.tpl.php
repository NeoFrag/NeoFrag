<ul class="forum-tree">
	<?php
		foreach ($categories as $category_id => $category)
		{
			echo '	<li><h3 class="m-0">'.icon('fa-navicon').' '.$category['title'].'</h3></li>
					<li>
						<ul class="forum-tree forum-tree-forums">';

			foreach ($category['forums'] as $forum_id => $forum)
			{
				echo '	<li><h4 class="m-0"><label><input type="radio" name="forum_id" value="'.$forum_id.'"'.($forum_id == $current ? ' checked="checked"' : '').' />'.icon('fa-comments-o').' '.$forum['title'].'</label></h4></li>';

				if ($forum['subforums'])
				{
					echo '	<li>
								<ul class="forum-tree forum-tree-subforums">';

					foreach ($forum['subforums'] as $subforum_id => $subforum)
					{
						echo '	<li><label><input type="radio" name="'.$token.'[forum_id]" value="'.$subforum_id.'"'.($subforum_id == $current ? ' checked="checked"' : '').' />'.icon('fa-comments-o').' '.$subforum.'</label></li>';
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
