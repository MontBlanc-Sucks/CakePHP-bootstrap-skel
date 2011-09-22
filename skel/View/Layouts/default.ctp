<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>
		<?php echo __('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('bootstrap.min', 'cake.generic', 'main'));

		echo $scripts_for_layout;
	?>

	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
</head>

<body>

	<div class="topbar">
		<div class="fill">
			<div class="container">
				<h3><a href="#">CakePHP: the rapid development php framework:</a></h3>
				<ul class="nav">
				<li class="active"><a href="#">Home</a></li>
				<li><a href="#about">About</a></li>
				<li><a href="#contact">Contact</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">

		<?php echo $this->Session->flash(); ?>
		<!-- Main hero unit for a primary marketing message or call to action -->
		<div class="row">
			<?php echo $content_for_layout; ?>
		</div>

		<footer>
		<?php echo $this->Html->link(
			$this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework'), 'border' => '0')),
				'http://www.cakephp.org/',
				array('target' => '_blank', 'escape' => false)
			);
		?>
		</footer>

	</div> <!-- /container -->

	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
