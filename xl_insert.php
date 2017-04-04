<?php
    require_once('./connect.php');
    Class Nested extends Connect  {
        Public $conn;
        Public $parent;
        Public function __construct(){
            $this->conn = $this->connectDB();
        }
        // CreateDB
        Public function createDB(){
            $createDB = "CREATE DATABASE nested";
            $this->conn->query($createDB);
        }
        // Create Table
        // Public function createTable(){
        //     $createTable = "CREATE TABLE Categories (
        //         id INT(6) auto_increment Primary Key,
        //         Category_name Varchar(30) Not Null,
        //         Category_parent_id INT(6) ,
        //         Category_left INT(6) ,
        //         Category_right INT(6) ,
        //         Category_date TIMESTAMP
        //         )";
        //     $this->conn->query($createTable);
        // }
        // Drop Table
        Public function DropTable(){
            $dropTable = "DROP Table Categories";
            $this->conn->query($dropTable);
        }
        // Select ($id)
        Public function selectID($id){
            $selectID = 'SELECT * FROM Categories WHERE id = '. $id;
            $result = $this->conn->query($selectID);
            $row = $result->fetch_assoc();
            return $row;
        }
        // Select All
        Public function selectAll(){
            $selectAll = 'SELECT * FROM Categories';
            $result = $this->conn->query($selectAll);
            return $result;
        }
        // Insert (Right)
        Public function insert($data , $parent = 0){
            $parentInfo = $this->selectID($parent);
            $parentRight = $parentInfo['Category_right'];

            $updateleft = 'UPDATE Categories SET Category_left = Category_left + 2 WHERE Category_left > '.$parentRight;
            $this->conn->query($updateleft);

            $updateright = 'UPDATE Categories SET Category_right = Category_right + 2 WHERE Category_right >= '.$parentRight;
            $this->conn->query($updateright);

            $left = $parentRight;
            $right = $parentRight + 1;
            $insert = "INSERT INTO Categories (Category_name , Category_parent_id , Category_left , Category_right) VALUES ('$data' , '$parent' , '$left' ,'$right')";
            $this->conn->query($insert);
        }
        // Remove
        Public function remove($id){
            $removeInfo = $this->selectID($id);
            $removerRight = $removeInfo['Category_right'];
            $removerLeft = $removeInfo['Category_left'];
            $remove = $removerRight - $removerLeft + 1;

            $delete  = "DELETE FROM Categories WHERE Category_left BETWEEN '$removerLeft' AND '$removerRight'";
            $this->conn->query($delete);

            $updateleft = "UPDATE Categories SET Category_left = (Category_left - '$remove') WHERE Category_left > ".$removerRight;
            $this->conn->query($updateleft);

            $updateright = "UPDATE Categories SET Category_right = (Category_right - '$remove') WHERE Category_right >".$removerRight;
            $this->conn->query($updateright);
        }
    }
