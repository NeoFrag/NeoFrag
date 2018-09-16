<div class="email">
	<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
		<tr>
			<td align="center">
				<table border="0" cellpadding="0" cellspacing="0" class="main-table">
					<tr>
						<td class="header">
							<a class="logo" href="<?php echo url('//') ?>"><img src="<?php echo NeoFrag()->model2('file', $this->config->nf_logo)->path() ?>" alt=""></a>
							<?php echo $header ?>
						</td>
					</tr>
					<tr>
						<td class="content">
							<?php echo $content ?>
						</td>
					</tr>
					<tr>
						<td class="footer">
							<?php echo $footer ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
