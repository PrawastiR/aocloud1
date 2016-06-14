<?php
require_once ('libs/init.php');
require_once ('libs/Connection.php');
redirectLogin();

if(!isset($_GET['id'])){
	$_SESSION['error'][] = "File tidak ditemukan";
	header('location: dashboard.php');
}


$stmt = $conn->prepare('SELECT * FROM file WHERE id = :id AND folder_id IN (SELECT id FROM folder WHERE user_id=:uid)');
$query = $stmt->execute([
	':id'=>$_GET['id'],
	':uid'=>$_SESSION['login']
]);

$file = $stmt->fetch(PDO::FETCH_OBJ);

if(!$file){
	$_SESSION['error'][] = "File tidak ditemukan";
	header('location: dashboard.php');
}


require_once ('inc/header.php');
showMessage();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View File</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-3 text-center">
				<br>
				<img src="img/<?= $file->ext ?>.png" alt="An Excel Worksheet Document">
				<div class="caption">
					<h4><?= $file->name ?></h4>
					<p>
						<?= $file->description ?>
					</p>
				</div>
				<br>
				<br>
				<div class="btn-group">
				  <button type="button" class="btn btn-primary">Action</button>
				  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
					<!--<li><a href="#"><i class="glyphicon glyphicon-upload"></i> Upload Revision</a></li>-->
					<li><a href="download.php?id=<?= $file->id?>"><i class="glyphicon glyphicon-download"></i> Download</a></li>
					<!--<li><a href="#"><i class="glyphicon glyphicon-share"></i> Share</a></li>-->
					<li role="separator" class="divider"></li>
					<li><a href="delete.php?id=<?= $file->id?>""><i class="glyphicon glyphicon-trash"></i> Delete</a></li>
				  </ul>
				</div>
			</div>
			<div class="col-sm-9">
				<h3 class="page-header" style="margin-top: 10px">About File</h3>
				<?php
				if($file->type == "Picture"){
					echo '<img src="'.$file->pname.'" />';
				}
				?>
				<!--<h3 class="page-header">Revision History</h3>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th width="40" class="text-center">#</th>
							<th width="100">Date Upload</th>
							<th width="150">File</th>
							<th>Note</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">1</td>
							<td>12 June 2015 12:54 PM</td>
							<td><a href=""><code>Rab Jembatan 2.xlsx</code></a></td>
							<td>Lorem ipsum dolor sit amet, vel ei rebum menandri maiestatis, vel sonet possit minimum cu. Vix ad enim laudem conclusionemque.</td>
						</tr>
					</tbody>
				</table>-->
			</div>
		</div>
	</div>
</div>
<?php include "inc/footer.php"; ?>