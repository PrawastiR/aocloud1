<?php
require_once ('libs/init.php');
require_once ('libs/Connection.php');
redirectLogin();



if(isset($_POST['nama_folder']) && isset($_POST['description'])){
	if(strlen($_POST['nama_folder']) < 4 || strlen($_POST['nama_folder']) > 32 ){
		$_SESSION['error'][] = "Panjang nama folder haru 4-32 karakter"; 
	}
	
	if(count($_SESSION['error']) == 0 ){
		$stmt = $conn->prepare('INSERT INTO folder (nama, description, user_id) VALUES (:nama, :des, :id)');
		$query=  $stmt->execute(array(
			':nama' => $_POST['nama_folder'],
			':des' => $_POST['description'],
			':id' => $_POST['login'],
		));
		
		if($query){
			$_SESSION['message'][] = "Folder berhasil ditambahkan";
			header('location: dashboard.php', true, 302);
			exit();
		}elsE{
			$_SESSION['error'][] = "Gagal dalam menambah folder";
			header('location: dashboard.php', true, 302);
			exit();
		}
	}
}

require_once ('inc/header.php');
showMessage();
?>
<div class="panel panel-default">
	<div class="panel-body">
		<a href="#" class="btn btn-info disabled" id="viewFile"><i class="glyphicon glyphicon-download"></i> View</a>
		<a href="#" class="btn btn-primary" id="downloadFile"><i class="glyphicon glyphicon-download"></i> Download</a>
		<!--<a href="#" class="btn btn-warning" id="shareFile"><i class="glyphicon glyphicon-share"></i> Share</a>-->
		<a href="#" class="btn btn-danger" id="deleteFile"><i class="glyphicon glyphicon-trash"></i> Delete</a>
		<a href="upload.php?folder=<?= $folder ?>" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-upload"></i> Upload</a>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-body" id="files">
			<?php
			$sql = 'SELECT * FROM file WHERE folder_id IN (SELECT id FROM folder WHERE user_id = '.$user->id.''.($folder==0?'':' AND folder_id = '.$conn->quote($folder)).') '.($type != '0' ? ' AND `type`='.$conn->quote($type) : '');
			
			$stmt = $conn->prepare($sql);
			$query = $stmt->execute();
			
			if($stmt->rowCount()){
				echo '<div class="row">';
			while($f = $stmt->fetch(PDO::FETCH_OBJ)){
			?>
			<div class="col-sm-4 col-md-3 item">
				<label for="checkbox_<?= $f->id ?>" style="font-weight: 400;">
					<input type="checkbox" class="hidden chk" id="checkbox_<?= $f->id ?>" value="<?= $f->id ?>" />
					<div class="thumbnail text-center">
						<br>
						<img src="img/<?= $f->ext ?>.png" alt="An Excel Worksheet Document">
						<div class="caption">
							<h4><?= $f->name ?></h4>
							<p><?= substr($f->description, 0, 200) ?></p>
					  </div>
					</div>
				</label>
		  </div>
			<?php } echo '</div>'; 
			}else{
				echo '<div class="alert alert-warning">Tidak ada file. Upload file untuk menambah. </div>';
			}
			?>
	</div>
</div>
<script type="text/javascript">
	function downloadAll(urls) {
	  var link = document.createElement('a');

	  link.setAttribute('download', null);
	  link.style.display = 'none';

	  document.body.appendChild(link);

	  for (var i = 0; i < urls.length; i++) {
		link.setAttribute('href', urls[i]);
		link.click();
	  }

	  document.body.removeChild(link);
	}
	$(".chk").change(function(){
		if($(".chk:checked").length != 1){
			$("#viewFile").addClass("disabled");
		}else{
			$("#viewFile").removeClass("disabled");
		}
	});
	
	$("#viewFile").click(function(){
		if(!$(this).hasClass("disabled")){
			location.href = "view.php?id="+$(".chk:checked").eq(0).val();
		}
	});
	$("#downloadFile").click(function(){
		var urls = [];
		for(var i = 0; i < $(".chk:checked").length; i++){
			urls.push("download.php?id="+$(".chk:checked").eq(i).val());
		};
		//console.log(urls);
		downloadAll(urls);
	});
	$("#deleteFile").click(function(){
		var url = "delete.php?";
		for(var i = 0; i < $(".chk:checked").length; i++){
			url += "id["+i+"]="+$(".chk:checked").eq(i).val()+"&";
		};
		location.href = url;
	});
</script>
<?php include "inc/footer.php"; ?>