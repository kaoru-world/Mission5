<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = uft-8>
        <title>Mission_5-01</title>
    </head>
    <body>
        <?php
        //MySQLに接続する
            $dsn = 'mysql:dbname=*****;host=*****';
            $user = '*****';
            $password = '*****';
            $pdo = new PDO($dsn, $user, $password, 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //MySQLに専用テーブルを作る  
            $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
                ." ("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "name char(32),"
                . "comment TEXT,"
                . "password TEXT,"
                . "date TEXT"
                .");";
                $stmt = $pdo->query($sql);
        //定義づけする
            $date = date("Y/m/d H:i:s");
        //新規投稿・事前準備
            if(!empty($_POST["submit"])){
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $password = $_POST["password"];
                $checkID = $_POST["checkID"];
            }
        //削除・事前準備
            if(!empty($_POST["delete"])){
                $deleteid = $_POST["id"];
                $deletepass = $_POST["pass"];
            }
        //編集・事前準備
            if(!empty($_POST["edit"])){
                $editID = $_POST["ID"];
                $editPASS = $_POST["PASS"];
            }
        //削除実行
        if(!empty($deleteid)){
            $sql = 'SELECT * FROM mission5_1 WHERE id=:id';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $deleteid, PDO::PARAM_INT); 
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                if ($deletepass == $row['password']){
                    $sql = 'DELETE FROM mission5_1 WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $deleteid, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        //編集段階１・フォーム再表示
        if(!empty($editID)){ 
            $sql = 'SELECT * FROM mission5_1 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $editID, PDO::PARAM_INT); 
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                if ($_POST["PASS"] == $row['password']){
                    $editnum_form = $row['id'];
                    $editname_form = $row['name'];
                    $editcom_form = $row['comment'];
                    $editpas_form = $row['password'];
                }
            }
        }
        //新規投稿＆編集段階２
         if(!empty($name) && !empty($comment)){
            if(!empty($checkID)){//編集
                $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,password=:password,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':id', $checkID, PDO::PARAM_INT);
                $stmt->execute();
            }else{//新規投稿    
                $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> execute();
            }
        }
        ?>
        <form action = ""method ="post">
            <input type = "text" name = "name"
                    placeholder = "名前を入力"
                    value="<?php if(!empty($editname_form)){echo $editname_form;}?>">
                <input type = "text" name = "comment"  
                    placeholder = "コメントを入力"
                    value="<?php if(!empty($editcom_form)){echo $editcom_form;} ?>">
                <input type = "number" name = "password"
                    placeholder = "パスワードを設定する"
                    value="<?php if(!empty($editpas_form)){echo $editpas_form;} ?>">
                <input type = "hidden" name = "checkID"
                    placeholder = "編集時確認番号"
                    value="<?php if(!empty($editnum_form)){echo $editnum_form;} ?>"> 
                <input type = "submit" name = "submit"
                    value = "投稿">
            <br>
            <input type = "number" name = "id"
                placeholder = "削除する投稿の番号">
                <input type = "number" name = "pass"
                    placeholder = "設定したパスワードを入力">
                <input type ="submit" name = "delete"
                    value = "削除実行">
            <br>
            <input type = "number" name = "ID"
                placeholder = "編集する投稿の番号">
                <input type = "number" name = "PASS"
                    placeholder = "設定したパスワードを入力">
                <input type = "submit" name = "edit"
                    value = "編集実行">
            <br>
        <?php
        $sql = 'SELECT * FROM mission5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date'].'<br>';
            echo '<hr>';
        }
        ?>
    </body>
</html>