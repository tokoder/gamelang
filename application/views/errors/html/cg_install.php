<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $heading; ?></title>
<style type="text/css">
html {
	height: 100%;
}
body {
	height: 100%;
	overflow: hidden;
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}
.title {
	color: #555 !important;
	margin: 0;
}
.version {
	margin-bottom: 60px;
	font-weight: 300;
	color: #999;
}
</style>
</head>
<body>
	<h1 class="title">
		<?php echo $heading; ?>
		<small class="version"><?php echo 'V'.CG_VERSION; ?></small>
	</h1>

	<h3>installing automatically</h3>
	<ul>
		<li>Click <a href="<?php echo config_item('base_url').'install';?>">here</a> (maintenance)</li>
	</ul>

	<h3>installing manually</h3>
	<ul>
		<li>Create database in DBMS [phpmyadmin, etc]</li>
		<li>Import sql_dump.sql</li>
		<li>Adjust database settings in application/config/database.php</li>
	</ul>
</body>
</html>