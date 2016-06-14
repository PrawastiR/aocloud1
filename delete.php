<?php
require_once ('libs/init.php');
require_once ('libs/Connection.php');
redirectLogin();

if(!isset($_GET['id'])){
	$_SESSION['error'][] = "File tidak ditemukan";
	header('location: dashboard.php');
}
$ids = [];
if(is_array($_GET['id'])){
	foreach($_GET['id'] as $id){
		$ids [] = $id;
	}
}else{
	$ids[] = $_GET['id'];
}

$stmt = $conn->prepare('DELETE FROM file WHERE id IN :ids AND folder_id IN (SELECT id FROM folder WHERE user_id= :id)');
$query = $stmt->execute([
':ids'=>$ids, 
':id'=>$_SESSION['login'], 
]);
if(!$query){
	$_SESSION['error'][] = "File gagal dihapus";
	header('location: dashboard.php');
}else{
	$_SESSION['message'][] = "File berhasil dihapus";
	header('location: dashboard.php');
}
?>