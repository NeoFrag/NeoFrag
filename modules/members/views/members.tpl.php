<?php if ($members): ?>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
	<?php foreach ($members as $member): ?>
		<div class="col mb-4">
			<div class="card card-member text-center<?php echo $this->user->id == $member->id ? ' border-primary' : '' ?>">
				<div class="m-auto pt-3"><?php echo $member->avatar() ?></div>
				<div class="card-body pt-0 px-0">
					<h6 class="card-title mb-0"><?php echo $member->username ?></h6>
					<?php
					$socials = $this->array([
										['website',   'fas fa-globe',       ''],
										['linkedin',  'fab fa-linkedin-in', 'https://www.linkedin.com/in/'],
										['github',    'fab fa-github',      'https://github.com/'],
										['instagram', 'fab fa-instagram',   'https://www.instagram.com/'],
										['twitch',    'fab fa-twitch',      'https://www.twitch.tv/']
									])
									->each(function($a) use ($member){
										if ($member->profile()->{$a[0]})
										{
											return '<a href="'.$a[2].$member->profile()->{$a[0]}.'" class="btn btn-primary btn-sm '.$a[0].'" target="_blank">'.icon($a[1]).'</a>';
										}
										else
										{
											return '<a href="#" class="btn btn-light btn-sm '.$a[0].' text-muted disabled">'.icon($a[1]).'</a>';
										}
									});
					?>
					<div class="socials mt-3"><?php echo $socials ?></div>
				</div>
				<?php if ($this->user()): ?>
				<div class="card-footer">
					<?php if ($this->user->id != $member->id): ?>
						<?php echo $this->button()->label('Contacter')->icon('far fa-envelope')->color('dark btn-sm')->url('user/messages/compose/'.$member->url()) ?>
					<?php else: ?>
						<?php echo $this->button()->label('Gérer mon compte')->icon('fas fa-cog')->color('primary btn-sm')->url('user') ?>
					<?php endif ?>
				</div>
				<?php endif ?>
			</div>
		</div>
	<?php endforeach ?>
</div>
<?php else: ?>
<div class="card border-info text-center">
	<div class="card-body"><?php echo $this->lang('Il n\'y a pas encore de membre dans ce groupe') ?></div>
</div>
<?php endif ?>
