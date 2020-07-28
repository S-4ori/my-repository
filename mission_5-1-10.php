<!DICTYPE html>
<html lang="ja">
    <head>
        <mata charset="UTF-8">
            <title>mission_5-1-10</title>
        </head>
    <body>
    
     
    <?php
     //Db接続設定//
     $dsn='データベース名';
     $user='ユーザー名';
     $password='パスワード';
     $pdo=new PDO($dsn,$user,$password, 
       array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING));
       
      //テーブル作成 
       $sql='CREATE TABLE IF NOT EXISTS tbts'
       ."("
       ."id INT AUTO_INCREMENT PRIMARY KEY,"
       ."name char(32),"
       ."comment TEXT,"
       ."date TEXT,"
       ."pass TEXT"
       .");";
       $stmt= $pdo->query($sql);
        
       //データ入力
       if(!empty($_POST["edt_num"])&&!empty($_POST["name"])&&!empty($_POST["comment"])){ //編集のとき
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["pass"];
        $id=$_POST["edt_num"]; 
        $sql='UPDATE tbts SET name=:name,comment=:comment WHERE id=:id';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':name',$name,PDO::PARAM_STR);
        $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
       }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){ //投稿のとき
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["pass"];
        $sql= $pdo->prepare("INSERT INTO tbts(name,comment,date,pass) 
        VALUES (:name,:comment,:date,:pass)");
    $sql->bindParam(':name',$name,PDO::PARAM_STR);
    $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
    $sql->bindParam(':date',$date,PDO::PARAM_STR);
    $sql->bindParam(':pass',$pass,PDO::PARAM_STR);
    $sql-> execute();
    }

    //削除機能
    if (!empty($_POST["delete"]) &&!empty($_POST["de_pass"])) { //削除と削除用パスワードが入ってるとき
        $dt_delete=$_POST["delete"];     
        $id = $dt_delete;
        $sql='SELECT * from tbts where id=:id';
        $stmt = $pdo->prepare($sql);                  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $row) {       
    if($_POST["de_pass"]==$row["pass"]){ //パスワードと削除用パスワードが一致するとき
        $sql = 'DELETE from tbts where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } 
}
    }
     
     //編集機能
     if (!empty($_POST["edit"]) &&!empty($_POST["edt_pass"])) {//編集と編集用パスワードが入ってるとき
        $dt_edit=$_POST["edit"];
        $edt_pass=$_POST["edt_pass"]; 
        $id=$dt_edit;
        $sql='SELECT * from tbts where id=:id';
        $stmt = $pdo->prepare($sql);  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();                         
        $results = $stmt->fetchAll();
        foreach ($results as $row) {
            if(!empty($edt_pass)){ //編集用パスワードが入っているとき
                if ($edt_pass==$row['pass']) {
                $edt_name=$row['name'];
                $edt_comment=$row['comment'];
                $edt_num=$row['id'];
            } 
        }
    }
}



?>    
<!--それぞれのフォームを作る-->
    <form action=" "method="post">
        <input type="text"name="name"placeholder="名前"value="<?php if (isset($edt_name)) {
    echo $edt_name;}?>"><br>        
        <input type="text"name="comment"placeholder="コメント"value="<?php if (isset($edt_comment)) {
    echo $edt_comment;};?>"><br>
        <input type="text"name="pass"placeholder="パスワード"><br>
        <input type="hidden" name="edt_num" value="<?php if (isset($edt_num)) {
    echo $edt_num;};?>">
         <input type="submit"value="送信"><br>
    
        <input type="text"name="delete"placeholder="削除番号"><br>
        <input type="text"name="de_pass"placeholder="パスワード"><br>
        <input type="submit"value="削除"> <br>

        <input type="text"name="edit"placeholder="編集番号"><br>
        <input type="text"name="edt_pass"placeholder="パスワード"><br>
        <input type="submit"value="編集"> <br>

     </form>
    <?php
     //データ表示
    $sql='SELECT * FROM tbts';
    $stmt = $pdo->prepare($sql);              
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchAll(); 
    foreach($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['date'].'<br>';
    echo "<hr>";    
}
?>    
</body>
    </html>