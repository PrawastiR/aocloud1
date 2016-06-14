<?php
require_once('libs/init.php');

if(isset($_POST['email']) && isset($_POST['nama']) && isset($_POST['password']) && isset($_POST['re_password'])){
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['error'][] = "Format Email Salah"; 
	}
	
	if(strlen($_POST['nama']) < 4 || strlen($_POST['nama']) > 32 ){
		$_SESSION['error'][] = "Panjang nama haru 4-32 karakter"; 
	}
	
	if(strlen($_POST['password']) < 4 || strlen($_POST['password']) > 32 ){
		$_SESSION['error'][] = "Panjang password haru 4-32 karakter"; 
	}
	
	if($_POST['password'] != $_POST['re_password']){
		$_SESSION['error'][] = "Ulangi password tidak sama"; 
	}
	
	if(count($_SESSION['error']) == 0 ){
		require_once 'libs/Connection.php';
		$params = [
			':nama'=>$_POST['nama'],
			':email'=>$_POST['email'],
			':password'=>md5($_POST['password'])
		];
		$stmt = $conn->prepare('INSERT INTO user (nama, email, password) 
		VALUES (:nama, :email, :password)');
		$query = $stmt->execute($params);
		
		if($query){
			$_SESSION['login'] = $conn->lastInsertId();;
			$_SESSION['message'][] = "Selamat datang di AO CLOUD";
			header('location: dashboard.php');
			exit();
		}elsE{
			$_SESSION['error'][] = "Gagal dalam menyimpan user";
		}
		
		header('location: index.php');
	}
}

header('location: index.php');
?>