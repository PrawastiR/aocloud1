<?php
require_once ('libs/init.php');
require_once ('libs/Connection.php');
redirectLogin();

if(!isset($_GET['id'])){
	$_SESSION['error'][] = "File tidak ditemukan";
	header('location: dashboard.php');
}
	
$stmt = $conn->prepare('SELECT * FROM file WHERE id = "'.$_GET['id'].'" AND folder_id IN (SELECT id FROM folder WHERE user_id='.$_SESSION['login'].')');
$query = $stmt->execute();

if($query){
	echo '<div class="row">';
	$file = $stmt->fetch(PDO::FETCH_OBJ);

if(!$file){
	$_SESSION['error'][] = "File tidak ditemukan";
	header('location: dashboard.php');
}

header("Content-disposition: attachment; filename=\"" . basename($file->pname) . "\""); 
readfile($file->pname); // do the double-download-dance (dirty but worky)
?>