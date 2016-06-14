<?php
require_once('libs/init.php');

if(isset($_POST['email']) && isset($_POST['password'])){
	require_once 'libs/Connection.php';
	
	$stmt = $conn->prepare('SELECT * FROM user WHERE email = :email AND password = :pw');
	$query = $stmt->execute([
		':email'=>$_POST['email'],
		':pw'=>md5($_POST['password'])
	]);
	
	if($query){
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		if($user){
			$_SESSION['login'] = $user->id;
			header('location: dashboard.php');
			exit();
		}elsE{
			$_SESSION['error'][] = "User tidak ditemukan. silahkan register untuk bergabung";
		}
		
		header('location: index.php');
	}elsE{
		$_SESSION['error'][] = "User tidak ditemukan. silahkan register untuk bergabung";
	}
	
}

header('location: index.php');
?>