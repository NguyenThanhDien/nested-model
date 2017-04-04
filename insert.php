<?php
	require_once 'kncsdl.php';
	$model = new databases();
?>
<form  action="add_category.php" method="post">
	<label> Name :</label><input type="text" name="name" value="">
	<label>Category</label>
	<select name="select_menu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
    <?php
			$model->select_option_categories();
		?>
  </select>
	<input type="radio" name="radio" value="right" checked="checked"/>Insert right
	<input type="radio" name="radio" value="left" />Insert left
	<input type="submit" name="submit" value="Submit">
</form>
