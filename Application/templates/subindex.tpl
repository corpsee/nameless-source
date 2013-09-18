<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand" href="#">Nameless Framework</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li class="nav-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
				</ul>
				<form class="navbar-form pull-right">
					<input class="span2" type="text" placeholder="Email">
					<input class="span2" type="password" placeholder="Password">
					<button type="submit" class="btn">Sign in</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="hero-unit">
		<h1>Nameless Framework</h1>
		<p>It is just another framework based on Symphony Components.</p>
		<p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
	</div>
	<div class="row">
		<div class="span6">
			<h2><?= $h2_en; ?></h2>
			<p><?= $p_en; ?></p>
			<p><a class="btn" href="#"><?= $btn_en; ?></a></p>
		</div>
		<div class="span6">
			<h2><?= $h2_ru; ?></h2>
			<p><?= $p_ru; ?></p>
			<p><a class="btn" href="#"><?= $btn_ru; ?></a></p>
		</div>
	</div>
	<hr>
	<footer>
		<p>&copy; Corpsee. 2013.</p>
	</footer>
</div>