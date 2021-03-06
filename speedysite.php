<?php
//Speedysite 1.1

$speedysite_version = '1.1';

$fields = array();
session_start();
if (!isset($_SESSION['ss_loggedin'])) {
	$_SESSION['ss_loggedin'] = false;
}

if (!file_exists('inf')) {
	mkdir('inf');

}

if (!file_exists('inf/config.php')) {
	file_put_contents("inf/config.php", '
<?php
//The Accounts.
$password = array("admin" => "password");
?>');

}

require_once('inf/config.php');

if (!isset($speedysite_file_name)) {
	$speedysite_file_name = 'speedysite.php';
}

$header = "
<script src=\"//tinymce.cachefly.net/4.0/tinymce.min.js\"></script>
<script type=\"text/javascript\">
tinymce.PluginManager.add('menusave', function(editor, url) {
    editor.addMenuItem('menusave', {
        text: 'Save',
        context: 'file',
        onclick: function() {
            $('.mce-i-save').closest('button').trigger('click');
        }
    });
});
tinymce.init({
    selector: \"div.prut8Eje\",
    inline: true,
    plugins: [
        \"advlist autolink lists link image charmap print preview anchor save\",
        \"searchreplace visualblocks code fullscreen\",
        \"insertdatetime media table textcolor contextmenu paste\"
    ],
	menu : { // this is the complete default configuration
        file   : {title : 'File'  , items : 'newdocument | print'},
        edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
        insert : {title : 'Insert', items : 'link media | template hr'},
        view   : {title : 'View'  , items : 'visualaid'},
        format : {title : 'Format', items : 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
        table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
        tools  : {title : 'Tools' , items : 'spellchecker code'}
    },
    toolbar: \"save | insertfile undo redo | styleselect  | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image\"
});
</script>
"; 
if (isset($showheader) AND $showheader != false or !isset($showheader)) {
	echo $header;
}
function ss_header($r = NULL) {
	global $header;
	if ($r) {
		return $header;
	}
	else {
		echo $header;
	}
}


if (isset($_POST['SaveSection']) and $_POST['SaveSection'] == true) {
	$thename = $_POST['name'];
        $namecontent = $_POST['name'] . '_content';
        $content1 = str_replace('\\', '', $_POST[$namecontent]);
        $content2 = str_replace('&amp;', '&', $content1); 
        $content = str_replace('&quot;', '', $content2);
        file_put_contents("inf/$thename.html", $content);
}
if (isset($_POST['saveothers']) and $_POST['saveothers'] == 'true&') {
	if ($_SESSION['ss_loggedin'] == true) {
		foreach($_POST as $thing=>$result) {
			if ($thing != 'saveothers' AND $result != 'true&') {
				file_put_contents("inf/$thing.html", $result);
			}
		}
	}
}
function ss($name, $default = NULL, $section = NULL, $type = NULL, $return = null) {
	global $fields;
	$file = "inf/$name.html";
	array_push($fields, array($name, $section, $type));
	if (!file_exists($file)) {
		if ($default) {
			file_put_contents($file, $default);
		}
		else {
			file_put_contents($file, '<p>Please edit this text on the Speedysite <a href="<?php echo $speedysite_file_name; ?>">admin</a> page.</p>');
		}
	}
	if (isset($_GET['p']) and $_GET['p'] == 'a' AND $_SESSION['ss_loggedin'] == true AND !$section) { 
?>
<form method="POST">
<input type="hidden" name="SaveSection" value="true" />
<input type="hidden" name="name" value="<?php echo $name; ?>">
<div class="prut8Eje" id="<?php echo $name; ?>_content" style="width:100%; height: auto;">
<?php echo file_get_contents($file); ?>
</div>
</form>
<?php
	}
	else {
		if ($return) { return file_get_contents($file); } else { echo file_get_contents($file); }
	}
}
function editothers($h=NULL) {
	global $fields;
	if (isset($_GET['p']) and $_GET['p'] == 'a' AND $_SESSION['ss_loggedin'] == true) {
		if ($h) { echo "<h2>$h</h2>"; } else { echo "<h2>Other Settings</h2>"; }
		echo "<form method='post' class='ss_otherform'>";
		echo "<input type='hidden' name='saveothers' value='true&' />";
		foreach($fields as $result) {
			if ($result[2] != 'textarea' AND $result[1] == 'othersettings') { ?>
				<label class="ss_label"><?php echo ucfirst($result[0]); ?>:</label> <input type="<?php echo $result[2]; ?>" class="ss_input" name="<?php echo $result[0]; ?>" value="<?php echo file_get_contents("inf/$result[0].html"); ?>" /><br />
			<?php 
			}
			elseif ($result[2] == 'textarea') { ?>
			<label class="ss_label"><?php echo ucfirst($result[0]); ?>:</label><br /><?php echo "<textarea class=\"ss_textarea\" name=\"$result[0]\">". file_get_contents("inf/$result[0].html") ."</textarea>"; ?>
			<?php
			} ?>
		<?php	
		}
		echo "<br /><input type='submit' class='ss_submit' value='Save' />";
		echo "</form>";
	}
}

function editmode() {
	if (isset($_GET['p']) and $_GET['p'] == 'a' and $_SESSION['ss_loggedin'] == true) {
		return true;
	}
	else {
		return false;
	}
}
function editbutton() {
	global $speedysite_file_name;
	if ($_SESSION['ss_loggedin'] == 'true') {
		if (!editmode()) {
			echo "<div style='position: fixed; top:0px; right: 0px; background:black; padding-left: 10px; padding-top: 10px; padding-right: 10px; border-top-left-radius: 5px;border-bottom-left-radius: 5px;'><p><a href='?p=a' style='color: blue;'>Edit</a></p></div>";
		}
		else {
			echo "<div style='width: 100%; height: 100px; background:black;'><center><br /><h4 style='text-decoration: underline; color: white; '>Speedysite Edit Mode</h4><p size: 18px; color: white;'><a href='$speedysite_file_name' style='color: blue;'>Dashboard</a> | <a href='?p=v' style='color: blue;'>Stop Editing</a></p></center></div>";
		}
	}
}

if (isset($_POST['login']) and $_POST['login'] == 'true') {
	require_once('inf/config.php');
	$username = $_POST['username'];
	$enteredpassword = $_POST['password'];
	if ($password[$username] == $enteredpassword) {
		$_SESSION['ss_loggedin'] = true;
	}
	else {
		echo "Login Failed. Try again";
	}
}
if (isset($_GET['p']) and $_GET['p'] == 'l') {
	$_SESSION['ss_loggedin'] = false;
}
if ( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Speedysite</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style type="text/css">
	.trash { color:rgb(209, 91, 71); }
	.flag { color:rgb(248, 148, 6); }
	.panel-body { padding:0px; }
	.panel-footer .pagination { margin: 0; }
	.panel .glyphicon,.list-group-item .glyphicon { margin-right:5px; }
	.panel-body .radio, .checkbox { display:inline-block;margin:0px; }
	.panel-body input[type=checkbox]:checked + label { text-decoration: line-through;color: rgb(128, 144, 160); }
	.list-group-item:hover, a.list-group-item:focus {text-decoration: none;background-color: rgb(245, 245, 245);}
	.list-group { margin-bottom:0px; }
	</style>
  </head>
  <body>
<?php if ($_SESSION['ss_loggedin'] == true) {

if(!isset($_SESSION['update_checks'])) {
	$_SESSION['update_checks'] = 0; 
}

if($_SESSION['update_checks'] < 20 or isset($allow_update_checks) and $allow_update_checks == true and $_SESSION['update_checks'] < 20) {
	$_SESSION['update_checks'] = $_SESSION['update_checks'] + 1;
	$url="https://api.github.com/repos/speedysnail6/speedysite/releases";

	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	// Execute
	$result=curl_exec($ch);
	// Closing
	curl_close($ch);

	$latest_version = str_replace('v', null, json_decode($result)[0]->tag_name);
	
	if ($latest_version > $speedysite_version) {
		$update_avalible = true;
	}
}

if (isset($_GET['u']) and isset($_GET['v']) and $_GET['u'] == 't') {
	$_SESSION['update_checks'] = $_SESSION['update_checks'] + 1;
	
	$url="https://raw.githubusercontent.com/Speedysnail6/Speedysite/v". $_GET['v'] ."/speedysite.php";
	
	if(file_put_contents($speedysite_file_name, file_get_contents($url))) {
		$update_success = true;
	}
	else {
		$update_success = false;
	}
	
}
?>
<div class="container">
	<br />
	<div class="jumbotron">
		<h2 style="text-align: center;">Speedysite Admin Zone <small>(<a href="?p=l">Log Out</a>)</small></h2>
		<?php
		if (isset($update_avalible) and $update_avalible == true) {
		?>
		<div class="alert alert-info alert-dismissible" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <strong>Speedysite <?php echo $latest_version; ?> is out!</strong> Click <a href="?u=t&v=<?php echo $latest_version; ?>">here</a> to instantly update.<strong>
		</div>
		<?php }
		if (isset($update_success) and $update_success == true) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <strong>Speedysite has updated to <?php echo $_GET['v']; ?>!</strong> <a href="<?php echo $speedysite_file_name; ?>">Refresh</a> to see changes.<strong>
		</div>
		<?php
		}
		elseif (isset($update_success) and $update_success == false) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <strong>There was an error updating to <?php echo $_GET['v']; ?>.</strong><strong>
		</div>
		<?php
		}
		?>
		<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<span class="glyphicon glyphicon-asterisk"></span>Content we could find
								</div>
								<div class="panel-body">
									<ul class="list-group">
										<?php
										$files = scandir('.');
										foreach($files as $files=>$files_value) {
											if(strpos($files_value,'.php') !== false AND $files_value != $speedysite_file_name) { ?>
												<li class="list-group-item">
													<span class="glyphicon glyphicon-file"></span>
													<div class="checkbox">
														<label for="checkbox">
															<?php echo ucfirst(str_replace('.php', '', $files_value)); ?>
														</label>
													</div>
													<div class="pull-right action-buttons">
														<a href="<?php echo "$files_value"; ?>" class="flag"><span class="glyphicon glyphicon-globe"></span></a>
														<a href="<?php echo "$files_value"; ?>?p=a"><span class="glyphicon glyphicon-edit"></span></a>
													</div>
											    </li>
											<?php }
										}
										?>
									</ul>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-12">
											<h6>
												To edit other files in subdirectories, just go to it and either click the edit button or add to the url "?p=a".</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<p>For help with Speedysite. Please take a look at the <a href="http://speedysnail6.com/speedysite">website</a>.
	</div>
</div>
<?php
} else { ?>
<div class="container"> 
		<hr class="prettyline">
		<br>
		<center>
		<h1><b>Speedysite</b></h1>
		<h3>Please enter your login details to begin editing!</h3>
		<em>Coded by <a href="http://speedysnail6.com">Speedysnail6</a></em>
		<br /><br />
	  <button class="btn btn-primary btn-lg" href="#signup" data-toggle="modal" data-target=".bs-modal-sm">Log In</button>
	  </center>
	  <br>
		<hr class="prettyline">
	 </div>
	  

	<!-- Modal -->
	<div class="modal fade bs-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
		<div class="modal-content">
			<br>
			<div class="bs-example bs-example-tabs">
				<ul id="myTab" class="nav nav-tabs">
				  <li class="active"><a href="#signin" data-toggle="tab">Sign In</a></li>
				  <li class=""><a href="#why" data-toggle="tab">Help</a></li>
				</ul>
			</div>
		  <div class="modal-body">
			<div id="myTabContent" class="tab-content">
			<div class="tab-pane fade in" id="why">
			<p><a href="http://speedysnail6.com/speedysite">Speedysite</a> is a lightweight Content Management System that allows users to manage websites without any coding knowledge.</p>
			</div>
			<div class="tab-pane fade active in" id="signin">
				<form method="POST" class="form-horizontal" action="<?php echo $speedysite_file_name; ?>">
				<input type="hidden" name="login" value="true">
				<fieldset>
				<!-- Sign In Form -->
				<!-- Text input-->
				<div class="control-group">
				  <label class="control-label" for="userid">Username:</label>
				  <div class="controls">
					<input required="" id="userid" name="username" type="text" class="form-control" placeholder="" class="input-medium" required="">
				  </div>
				</div>

				<!-- Password input-->
				<div class="control-group">
				  <label class="control-label" for="passwordinput">Password:</label>
				  <div class="controls">
					<input required="" id="passwordinput" name="password" class="form-control" type="password" class="input-medium">
				  </div>
				</div>

				<!-- Button -->
				<div class="control-group">
				  <label class="control-label" for="signin"></label>
				  <div class="controls">
					<button id="signin" name="signin" class="btn btn-success">Sign In</button>
				  </div>
				</div>
				</fieldset>
				</form>
			</div>
		</div>
		  </div>
		  <div class="modal-footer">
		  <center>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</center>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
}
?>