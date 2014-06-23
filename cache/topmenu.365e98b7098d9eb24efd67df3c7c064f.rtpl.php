<?php if(!class_exists('raintpl')){exit;}?>
<!-- Top Menu -->
<div class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<p class="navbar-brand visible-xs"><i class="glyphicon glyphicon-file"></i> Lists</p>
		</div>

		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#"><i class="glyphicon glyphicon-file"></i> <?php echo $lang["Lists"];?></a></li>
				<li><a href="#"><i class="glyphicon glyphicon-wrench"></i> <?php echo $lang["Settings"];?></a></li>
				<li><a href="#"><i class="glyphicon glyphicon-off"></i> Rémi Sarrailh</a></li>
			</ul>
	    </div><!--/.nav-collapse -->
</div>