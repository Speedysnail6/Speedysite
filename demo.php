<?php 
$showheader = true;
include_once('speedysite.php'); ?>
<!DOCTYPE html>
<html>
<body>
<head>
<?php ss_header(); ?>
</head>
<?php editbutton(); ?>
<div id="container" style="width: 100%">

<div id="header" style="background-color:#FFA500;">
<h1 style="margin-bottom:0;"><?php ss('header'); ?></h1></div>

<div id="menu" style="background-color:#FFD700;height:600px;width: 20%;float:left;">
<?php ss('menu', 'headersection'); ?>
</div>

<div id="content" style="background-color:#EEEEEE;height:600px;width:80%;float:left;">
<?php ss('content', NULL, NULL, 'This is the default text for the content zone'); ?></div>

<div id="footer" style="background-color:#FFA500;clear:both;text-align:center;">
<?php ss('footer'); ?></div>

</div>

</body>
</html> 