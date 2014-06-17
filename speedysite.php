<?php
session_start();
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
if ($showheader != true) {
	echo $header;
}
else {
	function ss_header($r = NULL) {
		global $header;
		if ($r) {
			return $header;
		}
		else {
			echo $header;
		}
	}
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
if ($_POST['SaveSection'] == true) {
	$thename = $_POST['name'];
        $namecontent = $_POST['name'] . '_content';
        $content1 = str_replace('\\', '', $_POST[$namecontent]);
        $content2 = str_replace('&amp;', '&', $content1); 
        $content = str_replace('&quot;', '', $content2);
        file_put_contents("inf/$thename.html", $content);
}
$fields = array('red');
function ss($name, $section = NULL, $type = NULL, $default = NULL) {
	global $fields;
	$file = "inf/$name.html";
	if (!file_exists($file)) {
		if ($default) {
			file_put_contents($file, $default);
		}
		else {
			file_put_contents($file, '<p>Please edit this text on the Speedysite <a href="speedysite.php">admin</a> page.</p>');
		}
	}
	if ($_GET['p'] == 'a' AND $_SESSION['ss_loggedin'] == true) { 
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
		array_push($fields, "blue","yellow");
		echo file_get_contents($file);
	}
}
if ($_POST['login'] == 'true') {
	require_once('inf/accounts.php');
	$username = $_POST['username'];
	$enteredpassword = $_POST['password'];
	if ($password[$username] == $enteredpassword) {
		$_SESSION['ss_loggedin'] = true;
	}
	else {
		echo "Login Failed. Try again";
	}
}
function editbutton() {
	if ($_SESSION['ss_loggedin'] == 'true') {
		echo "<div style='position: fixed; top:0px; right: 0px;'><p><a href='?p=a'>Edit</a></p></div>";
	}
}
if ( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Speedysite | Log in</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
<?php if ($_SESSION['ss_loggedin'] == true) {
header("Location: index.php");
} else { ?>
<div class="container"> 
		<hr class="prettyline">
		<br>
		<center>
		<h1><b>Speedysite</b></h1>
		<h3>Please log in to edit the pages</h3>
		<em>Coded by <a href="http://speedysnail6.com">Speedysnail6</a></em>
		<br>
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
			<p>Speedysite is a lightweight Content Management System that allows you to edit websites without any coding knowledge.</p>
			</div>
			<div class="tab-pane fade active in" id="signin">
				<form method="POST" class="form-horizontal">
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