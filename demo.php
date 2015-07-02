<?php
//Tells speedysite not to plop the header on the top, but to let us handle it
$showheader = false;

//Adds speedysite
require_once('speedysite.php'); ?>
<!DOCTYPE html>
<html>
<head>
<?php
//Imports the header for speedysite.
ss_header();
?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<title><?php ss('Title', 'Speedysite Demo', 'othersettings', 'text'); ?></title>
</head>
<body>
<?php
//This makes a small edit button appear on the page when the user is logged in
editbutton();
?>
<?php
$hello = `dasfffffffffffffffffffffffffff`;
echo $hello;
?>
<br />
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> 
					 <a class="navbar-brand" href="demo.php">Speedysite Demo</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="demo.php">Home</a>
						</li>
						<li>
							<a href="http://speedysnail6.com/speedysite">Website</a>
						</li>
						<li>
							<a href="http://github.com/speedysnail6/speedysite/">GitHub</a>
						</li>
						<li>
							<a href="http://speedysnail6.com/speedysite/wiki/">Docs</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="speedysite.php">speedysite.php</a>
						</li>
					</ul>
				</div>
				
			</nav>
			<div class="jumbotron">
				<?php ss('mainsection', '<h1>
					This is Speedysite
				</h1>
				<p>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to the Speedysite Demo page! To log on, go to <a href="speedysite.php">speedysite.php</a>, go back to this page, and click the little edit button on the right or click the big blue button!
				</p>
				<p>
					<a class="btn btn-primary btn-lg" href="?p=a">Start Editing</a>
				</p>'); ?>
			</div>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-4 column">
			<?php ss('demo1stcolumn', '<h2>
				Why use Speedysite?
			</h2>
			<p>
				You only need to upload one file to install Speedysite, and not even that. On our <a href="http://speedysnail6.com/speedysite">website</a> you can give the site the FTP details, and it&#39;ll do it for you. You can even configure the accounts and stuff from there! No pesky file configuration needed.
			</p>
			<p>
				<a class="btn btn-default" href="http://github.com/speedysnail6/speedysite">View details &#187;</a>
			</p>'); ?>
		</div>
		<div class="col-md-4 column">
			<?php ss('demo2ndcolumn', '<h2>
				Umm... why else?
			</h2>
			<p>
				Although this demo page may be confusing, you only need one line of code that we give you to connect to Speedysite, and another to make a specific section editable. It&#39;s much easier than hooking it up with a big CMS such as WordPress or Drupal.
			</p>
			<p>
				<a class="btn btn-default" href="http://github.com/speedysnail6/speedysite">View details &#187;</a>
			</p>'); ?>
		</div>
		<div class="col-md-4 column">
			<?php ss('demo3rdcolumn', '<h2>
				How much does it cost?
			</h2>
			<p>
				Speedysite is 100% free for everybody! This can be used for commercial or personal use and you can modify it without attribution!
			</p>
			<p>
				<a class="btn btn-default" href="http://github.com/speedysnail6/speedysite">View details &#187;</a>
			</p>'); ?>
		</div>
	</div>
	<?php
	if (editmode()) {
	?>
	<div class="row clearfix">
		<hr />
		<div class="container">
			<?php editothers('Preferences'); ?>
		</div>
	</div>
	<?php
	}
	?>
</div>
</body>
</html> 
