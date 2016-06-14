<?php
require_once('libs/init.php');

unset($_SESSION['login']);
header('location: index.php');
?>