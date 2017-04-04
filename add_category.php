<?php
	require_once 'kncsdl.php';
	$model = new databases();
  $name = $_POST['name'];
	$radio = $_POST['radio'];
  $parent = $_POST['select_menu'];
  if(isset($_POST['submit'])){
		if($radio == "left"){
			$model->Add_Left($name,$parent);
			header ("location: index.php");
		}
		elseif($radio == "right"){
		   $model->Add_Right($name,$parent);
			 header ("location: index.php");
		}else{
			echo "error";
		}
  }
?>
