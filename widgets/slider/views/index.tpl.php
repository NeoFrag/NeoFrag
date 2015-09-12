<div id="carousel" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#carousel" data-slide-to="0" class="active"></li>
		<li data-target="#carousel" data-slide-to="1"></li>
	</ol>
	<div class="carousel-inner" role="listbox">
		<div class="item active">
			<img src="<?php echo image('slide1.jpg'); ?>" alt="..." />
			<div class="carousel-caption hidden-xs">
				<h3>Slider 1</h3>
				<p>Accedebant enim eius asperitati, ubi inminuta vel laesa amplitudo imperii dicebatur</p>
			</div>
		</div>
		<div class="item">
			<img src="<?php echo image('slide2.jpg'); ?>" alt="..." />
			<div class="carousel-caption hidden-xs">
				<h3>Slider 2</h3>
				<p>Accedebant enim eius asperitati, ubi inminuta vel laesa amplitudo imperii dicebatur</p>
			</div>
		</div>
	</div>
	<a class="left carousel-control hidden-xs" href="#carousel" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control hidden-xs" href="#carousel" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>