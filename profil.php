<?php
require_once ('libs/init.php');
require_once ('libs/Connection.php');
redirectLogin();


if(isset($_POST['email']) && isset($_POST['nama']) && isset($_POST['password']) && isset($_POST['re_password'])){
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['error'][] = "Format Email Salah"; 
	}
	
	if(strlen($_POST['nama']) < 4 || strlen($_POST['nama']) > 32 ){
		$_SESSION['error'][] = "Panjang nama haru 4-32 karakter"; 
	}
	
	if(count($_SESSION['error']) == 0 ){
		$params = [
			':id'   =>$_SESSION['login'],
			':nama' => $_POST['nama'],
			':email' => $_POST['email'],
		];
		$sql = 'UPDATE user SET nama = :nama, email = :email ';
		if($_POST['password'] != '' AND $_POST['re_password'] != ''){
			if(strlen($_POST['password']) < 4 || strlen($_POST['password']) > 32 ){
				$_SESSION['error'][] = "Panjang password haru 4-32 karakter"; 
			}
			
			if($_POST['password'] != $_POST['re_password']){
				$_SESSION['error'][] = "Ulangi password tidak sama"; 
			}
			
			
			if(count($_SESSION['error']) == 0 ){
				$sql .= ', password= :pw';
				$params[':pw'] = md5($_POST['password']);
			}
		}
		$sql .= ' WHERE id= :id';
		
		$stmt = $conn->prepare($sql);
		;
		
		if($stmt->execute($params)){
			$_SESSION['message'][] = "Profil berhasil disimpan";
			header('location: profil.php', true, 302);
			exit();
		}elsE{
			$_SESSION['error'][] = "Gagal dalam mengupdate profile";
		}
	}
}

require_once ('inc/header.php');
showMessage();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Profile</h3>
	</div>
	<div class="panel-body" id="files">
		<form class="form-horizontal" method="POST" action="profil.php">
		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>
			<div class="col-sm-10">
			  <input type="text" name="nama" class="form-control" value="<?= $user->nama ?>" placeholder="Nama Lengkap">
			</div>
		  </div>
		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
			  <input type="email" name="email" class="form-control" value="<?= $user->email ?>" placeholder="Email">
			</div>
		  </div>
		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Password</label>
			<div class="col-sm-10">
			  <input type="password" name="password" class="form-control" placeholder="Hanya diisi jika ingin ganti password">
			</div>
		  </div>
		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-2 control-label">Ulangi Password</label>
			<div class="col-sm-10">
			  <input type="password" name="re_password" class="form-control" placeholder="Hanya diisi jika ingin ganti password">
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-primary">Update</button>
			</div>
		  </div>
		</form>
	</div>
</div>
<?php include "inc/footer.php"; ?>