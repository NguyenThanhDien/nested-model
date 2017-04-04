<?php
  class databases{
    public function __construct(){
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "nested";
      $conn = mysqli_connect($servername, $username, $password,$dbname);
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }else{
        return $this->conn = $conn;
      }
    }
    Public function createTable(){
            $createTable = "CREATE TABLE Categories (
                id INT(11) auto_increment Primary Key,
                name Varchar(50) Not Null,
                parent INT(1) ,
                lft INT(1) ,
                rgt INT(11) ,
                level INT(11)
                )";
            $this->conn->query($createTable);
        }
    Public function select_categories(){
        $sql = 'SELECT * FROM Categories ORDER BY lft ASC';
        $result = $this->conn->query($sql);
        ?>
        <table>
          <tr>
            <th>Id</th>
            <th>Parent</th>
            <th>Level</th>
            <th>Name</th>
            <th>Left</th>
            <th>Right</th>
            <th>control</th>
          </tr>
        <?php foreach ($result as $row): ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['parent']; ?></td>
            <td><?php echo $row['level']; ?></td>
            <td><?php
            $x = 1;
            while($x <= $row['level']) {
                echo "- ";
                $x++;
            }
            echo $row['name']; ?></td>
            <td><?php echo $row['lft']; ?></td>
            <td><?php echo $row['rgt']; ?></td>
            <td><a href="edit.php?id=<?php echo $row['id']; ?>&idparent=<?php echo $row['parent'] ?>"> Edit </a>
             - <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a></td>
          </tr>
        <?php endforeach; ?>
        </table>
      <?php
    }
    // public function update_categories($name , $parent, $id){
    //   if($id != null && $id != 0){
    //     $nodeInfo = $this->search_id($id);
    //     $strUpdate = $this->createUpdateQuery($name);
    //     $sql	= "UPDATE Categories SET '$parent' WHERE id = '$id'";
    //     $this->conn->query($insert);
    //   }
    //
    //   if($newParentId != null && $newParentId > 0){
    //     if($nodeInfo['parents'] != $newParentId){
    //       $this->moveNode($id,$newParentId);
    //     }
    //   }
    // 	}
    Public function select_parent($id){
        $sql = "SELECT * FROM categories WHERE id = '$id'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
    Public function delete($id){
        $deleteInfo = $this->search_id($id);
        $deleteRight = $deleteInfo['rgt'];
        $deleteLeft = $deleteInfo['lft'];
        $sql  = "DELETE FROM Categories WHERE lft BETWEEN '$deleteLeft' AND '$deleteRight'";
        $this->conn->query($sql);
        $updateleft = "UPDATE Categories SET lft = (lft - 2) WHERE lft > ".$deleteRight;
        $this->conn->query($updateleft);
        $updateright = "UPDATE Categories SET rgt = (rgt - 2) WHERE rgt >".$deleteRight;
        $this->conn->query($updateright);
    }

    Public function update_categories($name , $parent, $id){
      $sql1 =$this->select_parent($id);
      $idright= $sql1['rgt'];
      $updateleft1 = 'UPDATE categories SET lft = lft - 2 WHERE lft > '.$idright;
      $this->conn->query($updateleft1);
      $updateright1 = "UPDATE categories SET rgt = rgt - 2 WHERE rgt > '.$idright.' && lft > 0";
      $this->conn->query($updateright1);
      $updateright2 = "UPDATE categories SET rgt = rgt  WHERE rgt = '.$idright.' && lft = 0";
      $this->conn->query($updateright2);

      $parentInfo = $this->select_parent($parent);
      $parentRight = $parentInfo['rgt'];
      $parentLeft = $parentInfo['lft'];
      $updateleft = "UPDATE categories SET lft = lft + 2 WHERE lft > '$parentRight'";
      $this->conn->query($updateleft);
      $updateright2 = "UPDATE categories SET rgt = rgt WHERE rgt ='$parentRight' && lft = 0 ";
      $this->conn->query($updateright2);
      $updateright = "UPDATE categories SET rgt = rgt + 2 WHERE rgt >='$parentRight' && lft > 0";
      $this->conn->query($updateright);
      $left = $parentRight;
      $right = $parentRight +1;
      $level 		= $parentInfo['level'] + 1;
      $insert = "UPDATE categories SET name = '$name',parent = '$parent',lft = '$left',rgt = '$right',level = '$level' WHERE id = '$id'";
      $this->conn->query($insert);
    }
    public function add_categories($name,$id){
      $sql = "SELECT * FROM categories WHERE id = '$id'";
      $result = $this->conn->query($sql);
      $row = $result->fetch_assoc();
      if($row['id'] == TRUE){
        $this->insert_categories($name,$id);
        header ("location: index.php");
      }else{
        echo "Thong tin chua day du";
      }
    }
    public function Add_Left($name,$parent){
    		$parentInfo =  $this->search_id($parent);
    		$parentleft = $parentInfo['lft'];
        $updateleft = "UPDATE Categories SET lft = lft + 2 WHERE lft > ".$parentleft;
    		$this->conn->query($updateleft);
        $updateRight = "UPDATE Categories SET rgt = rgt + 2 WHERE rgt > ".($parentleft + 1);
    		$this->conn->query($updateRight);
    		$left		= $parentleft + 1;
    		$right	= $parentleft + 2;
    		$level 		= $parentInfo['level'] + 1;
        $insert = "INSERT INTO categories (name,parent,lft,rgt,level)
        VALUES ('$name' , '$parent' , '$left' ,'$right','$level')";
        $this->conn->query($insert);
    	}
    Public function Add_Right($name ,$parent){
        $parentInfo = $this->search_id($parent);
        $parentRight = $parentInfo['rgt'];
        $updateleft = 'UPDATE categories SET lft = lft + 2 WHERE lft > '.$parentRight;
        $this->conn->query($updateleft);
        $updateright = 'UPDATE categories SET rgt = rgt + 2 WHERE rgt >= '.$parentRight;
        $this->conn->query($updateright);
        $left = $parentRight;
        $right = $parentRight + 1;
        $level 		= $parentInfo['level'] + 1;
        $insert = "INSERT INTO categories (name,parent,lft,rgt,level)
        VALUES ('$name' , '$parent' , '$left' ,'$right','$level')";
        $this->conn->query($insert);
    }
    Public function id_name($id){
        $sql = "SELECT name FROM categories WHERE id = '$id'";
        $result = $this->conn->query($sql);
        foreach ($result as $row){
          echo $row['name'];
        }
    }
    Public function edit_select_option_categories($idparent){
        $sql = "SELECT * FROM categories WHERE id = '$idparent' ORDER BY lft ASC";
        $result = $this->conn->query($sql);
        foreach ($result as $row) {
          echo "<option value=".$row['id'].">";
          $x = 1;
          while($x <= $row['level']) {
              echo "- ";
              $x++;
          }
          echo $row['name'];
          echo "</option>";
        }
    }
    Public function search_id($id){
        $sql = "SELECT * FROM categories WHERE id = '$id'";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row;
    }
    Public function select_option_categories(){
        $sql = "SELECT * FROM categories ORDER BY lft ASC";
        $result = $this->conn->query($sql);
        foreach ($result as $row) {
          echo "<option value=".$row['id'].">";
          $x = 1;
          while($x <= $row['level']) {
              echo "- ";
              $x++;
          }
          echo $row['name'];
          echo "</option>";
        }
    }
    public function menu(){
      $sql = "SELECT * FROM categories WHERE level = 1";
      $result = $this->conn->query($sql);
      foreach ($result as $row) {
        $level = $row['level'];
        $id = $row['id'];?>
        <li>
          <a href="#">
            <?php  echo $row['name'];  ?>
          </a><?php
            $sql1 = "SELECT * FROM categories WHERE parent = '$id' && level = 2";
            $result1 = $this->conn->query($sql1);
            foreach ($result1 as $row1){
              $id1 = $row1['id'];?>
              <ul class="ul-2">
                <li><a href="#"><?php echo $row1['name'];  ?></a>
                  <?php
                      $sql2 = "SELECT * FROM categories WHERE parent = '$id1' && level = 3";
                      $result2 = $this->conn->query($sql2);
                      foreach ($result2 as $row2){ ?>
                        <ul class="ul-3">
                          <li><a href="#"><?php echo " - ".$row2['name'];  ?></a></li>
                        </ul>
                      <?php } ?>
                </li>
              </ul>
              <?php } ?>
        </li>
      <?php
    }
  }
}
?>
