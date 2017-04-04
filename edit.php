<?php
	require_once 'kncsdl.php';
	$model = new databases();
  $id = $_GET['id'];
  $idparent = $_GET['idparent'];
?>
<form  action="xl_edit.php" method="post">
  <input name="id_categories" type="hidden" value="<?php echo $id; ?>" />
	<label> Name :</label><input type="text" name="name" value="<?php $model->id_name($id); ?>">
	<label>Category</label>
	<select name="edit_menu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
    <?php
			$model->edit_select_option_categories($idparent);
      $model->select_option_categories();
		?>
  </select>
  <!-- <input type="radio" name="radio" value="default"checked="checked"/>Default
  <input type="radio" name="radio" value="left" />Insert left -->
	<input type="submit" name="edit_submit" value="Submit">
</form>
