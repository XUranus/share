<?php $fileErr = 'File Size Limit '.min(ini_get('upload_max_filesize'), ini_get('post_max_size'));?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Share - Powered by XUranus</title>
		<link rel="stylesheet" href="css/icon.css">
		<script defer src="js/material.min.js"></script>
		<link rel="stylesheet" href="css/material.amber-yellow.min.css" />
		<script src="js/jquery-1.11.1.min.js"></script>
	</head>
	<body>
		<!-- Always shows a header, even in smaller screens. -->
		<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
			<header class="mdl-layout__header">
				<div class="mdl-layout__header-row">
					<!-- Title -->
					<span class="mdl-layout-title">Share</span>
					<!-- Add spacer, to align navigation to the right -->
					<div class="mdl-layout-spacer"></div>
					<!-- Navigation. We hide it in small screens. -->
					<nav class="mdl-navigation">
						<a class="mdl-navigation__link" href="">Adminstrator Login</a>
					</nav>
				</div>
			</header>
			<div class="mdl-layout__drawer">
				<span class="mdl-layout-title">Share</span>
				<nav class="mdl-navigation">
					<a class="mdl-navigation__link" href="">Login</a>
				</nav>
			</div>
			<main class="mdl-layout__content">
				<div class="page-content">
				<!-- Your content goes here -->
				
						<div style="text-align:center;margin:40px 40px 40px 40px;">
							<form style="width:100%" action="index.php" method="post" enctype="multipart/form-data">
								<h5> <?php echo $fileErr;?> </h5>
								<input type="text"   class="mdl-textfield__input" id="textfield" value="File Path..." contenteditable="false"><br>
								<input type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"  value="Select" id="selector">  &nbsp; &nbsp; &nbsp; 
								<input type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"  value="upload" name="submit">  
								<input type="file"   style="display:none" contenteditable="false"  onchange="document.getElementById('textfield').value=this.value" id="file" name="file[]" multiple>  
								<script>
									$(document).ready(function(){
										 $("#selector").click(function(){
											$("#file").click();
										 });
										});
								</script>
							</form>
						<div><br>	
							

				<table class="center mdl-cell mdl-cell--12-col mdl-data-table mdl-js-data-table">
			


<?php 
	function allow_file_type($filetype){
		$right = 1;	
		if($filetype == "text/html")
			$right = 0;
		if($filetype == "application/x-php")
			$right = 0;
		return $right;
	}
	
	if(isset($_POST["submit"])){
		if($_POST["submit"] == "upload"){
			for($i = 0;$i < count($_FILES["file"]["name"]);$i++){
				if(allow_file_type($_FILES["file"]["type"][$i])){
					if($_FILES["file"]["error"][$i] > 0){
						echo 
						$fileErr = 'Error Code: '.$_FILES["file"]["error"][$i];
					}
					else{
						if(file_exists('upload/'.$_FILES["file"]["name"][$i])){
							$fileErr = $_FILES["file"]["name"][$i].' already exists.';
						}
						else{
							$Name = $_FILES["file"]["name"][$i];
							$Type = $_FILES["file"]["type"][$i];
							$Size = $_FILES["file"]["size"][$i];
							date_default_timezone_set("Asia/Shanghai");
							$Time = date("Y-m-d H:i:s");
							move_uploaded_file($_FILES["file"]["tmp_name"][$i], 'upload/'.$_FILES["file"]["name"][$i]);
						}
					}
				}
				else{
					$fileErr =  'Invalid file type.';
				}
			}	
	}
		else if($_POST["submit"] == "delete"){
			$Name = 'upload/'.$_POST['name'];
			unlink($Name);
			echo '<meta http-equiv="refresh" content="0;url=index.php">';
		}
		else{
			echo '<meta http-equiv="refresh" content="0;url=denied.html">';
		}
	}



	$dir = 'upload';
	if(is_dir($dir)){
		if($dh = opendir($dir)){
			while(($file = readdir($dh))){
				if($file == "."||$file == ".."||$file == "index.html")continue;
				$key = filemtime("upload/$file");
				$files[$file] = $key; 
			}
			if(!empty($files)){
				arsort($files);
				foreach($files as $file => $key){
					$Link = '<a class="mdl-button mdl-js-button mdl-js-ripple-effect" href="upload/'.$file.'">'.$file.'</a>';
					$Size = filesize("upload/$file");
					$SizeKiB = round($Size / 1024, 2);
					$SizeMiB = round($Size / 1024 / 1024, 2);
					if($SizeMiB > 1)
						$Size = $SizeMiB.' M';
					else
						$Size = $SizeKiB.' K';
					$Time = date("Y-m-d H:i", filemtime("upload/$file"));
					$Download = '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--primary mdl-js-ripple-effect" href="upload/'.$file.'" download="'.$file.'">Download</a>';
					$Delete = '
						<form action="index.php" method="post">
							<input type="hidden" name="name" value="'.$file.'">
							<label><a class="mdl-button mdl-js-button mdl-js-ripple-effect">Delete</a><input style="display:none" type="submit" name="submit" value="delete"></label>
						</form>';//还没加入 等待检测 没有样式
					
					echo	'<tr>
								<td class="mdl-data-table__cell--non-numeric">'.$Link.'</td>
								<td class="mdl-data-table__cell--non-numeric">'.$Time.'</td>
								<td class="mdl-data-table__cell--non-numeric">'.$Size.'</td>
								<td></td>
								<td>'.$Download.'</td>
								<td>'.$Delete.'</td>
								
					      	</tr>';
				}
			}
			closedir($dh);
		}
	}
	

?>






				</table>
	
					
				</div>
			</main>
			<footer class="mdl-mini-footer">
				<div class="mdl-mini-footer__left-section">
					<div class="mdl-logo">Share</div>
					<ul class="mdl-mini-footer__link-list">
						<li><a href="#">Help</a></li>
						<li><a href="#">Privacy & Terms</a></li>
					</ul>
				</div>
				<div class="mdl-mini-footer__right-section">
					<div class="mdl-logo">Copyright &copy; 2017 XUranus All Rights Reserved</div>
				</div>
			</footer>
		</div>
	</body>
</html>