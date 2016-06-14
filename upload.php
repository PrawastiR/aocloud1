<?php
require_once ('libs/init.php');
require_once ('libs/Connection.php');
redirectLogin();


if(isset($_FILES['file']) && isset($_POST['nama']) && isset($_POST['type']) && isset($_POST['description']) && isset($_POST['folder'])){
	
	if(strlen($_POST['nama']) < 1 || strlen($_POST['nama']) > 250 ){
		$_SESSION['error'][] = "Panjang nama haru 1-250 karakter"; 
	}
	$target_dir = "uploads/user-".$_SESSION['login'].'/';
	if(!file_exists($target_dir)){
		@mkdir($target_dir);
	}
	
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$ex = explode(".", basename($_FILES["file"]["name"]));
	$ext = end($ex);
	
	$allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'xls', 'xlsx', 'ppt', 'pptx'];
	
	if(count($_SESSION['error']) == 0 ){
		if(in_array(strtolower($ext), $allowed)){
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
				$params = [
					':type'=>$_POST['type'],
					':name'=>$_POST['name'],
					':ext'=>$ext,
					':pname'=>$target_file,
					':description'=>$_POST['description'],
					':folder_id'=>$_POST['folder'],
				];
				$stmt = $conn->prepare('INSERT INTO file (type, name, ext, pname, description, folder_id) 
				VALUES (:type, :name, :ext, :pname, :description, :folder_id)');
				$query = $stmt->execute($params);
				
				if($query){
					$_SESSION['message'][] = "File berhasil disimpan";
					header('location: dashboard.php', true, 302);
					exit();
				}elsE{
					$_SESSION['error'][] = "Gagal dalam mengupload file";
				}
			} else {
				$_SESSION['error'][] = "File gagal di upload";
			}
		}else{
			$_SESSION['error'][] = "Extensi File Tidak Diperbolehkan";
		}
	}
}

require_once ('inc/header.php');
showMessage();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Upload File</h3>
	</div>
	<div class="panel-body" id="files">
		<form class="form-horizontal" method="POST" action="upload.php?folder=<?= $folder ?>" enctype="multipart/form-data">
		  <div class="form-group">
			<label class="col-sm-2 control-label">File</label>
			<div class="col-sm-10">
			  <input type="file" name="file" placeholder="File">
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label">Folder</label>
			<div class="col-sm-10">
				<select name="folder" class="form-control">
					<?php
					$sql = 'SELECT * FROM folder WHERE user_id=:id';
									
					$stmt = $conn->prepare($sql);
					$query = $stmt->execute([
						':id'=>$user->id
					]);
					while($f = $stmt->fetch(PDO::FETCH_OBJ)){
					?>
					<option value="<?= $f->id ?>" <?= $f->id == $folder ? 'selected':'' ?>><?= $f->nama ?></option>
					<?php } ?>
				</select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label">Nama File</label>
			<div class="col-sm-10">
			  <input type="text" name="nama" class="form-control" placeholder="Nama File">
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label">Type</label>
			<div class="col-sm-10">
			  <select name="type" class="form-control">
				<option value="File">File</option>
				<option value="Document">Document</option>
				<option value="Picture">Picture</option>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10">
			  <textarea name="description" class="form-control" placeholder="Description"></textarea>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-primary">Upload</button>
			</div>
		  </div>
		</form>
	</div>
</div>
<?php include "inc/footer.php"; ?>