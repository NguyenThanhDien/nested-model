<?php
	require_once 'kncsdl.php';
	$model = new databases();
  $name = $_POST['name'];
  // $radio = $_POST['radio'];
  $parent = $_POST['edit_menu'];
  $id = $_POST['id_categories'];
  if(isset($_POST['edit_submit'])){
		// if($radio == "left"){
		// 	$model->update_Left($name,$parent, $id);
		// 	header ("location: index.php");
		// }
		// elseif($radio == "default"){
      $model->update_categories($name , $parent, $id);
      header ("location: index.php");
		//}
  }

?>
