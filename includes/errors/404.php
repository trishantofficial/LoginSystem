<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/LoginSystem/core/init.php');
if(Session::exists('error')) {
	echo Session::flash('error') . '<br>';
}
echo ' Try again please --> <a href="index.php">Home</a>';
?>