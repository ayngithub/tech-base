<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission_5-1</title>
    </head>
    <body>
        
        みんなの今行きたい旅行先教えて！！＾＾<br>
        
        <br>
        
        <?php

            //*****DB接続設定*****

            $dsn = 'データベース名' ;
            $user = 'ユーザー名' ;
            $password = 'パスワード' ;
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)) ;


            //*****テーブル作成*****
            
            $sql='CREATE TABLE IF NOT EXISTS tbtest
                   (id INT AUTO_INCREMENT PRIMARY KEY, name CHAR(32), comment TEXT, date TEXT, pw TEXT)';
            $stmt=$pdo->query($sql);
            
            
            //*****投稿＆編集機能*****
            
            //送信ボタンが押されたとき以下を実行する
            if(isset($_POST["submit"])) {
                //変数定義
                $name=$_POST["name"];           
                $comment=$_POST["comment"];
                $date=date("Y/m/d H:i:s");
                $pw1=$_POST["pw1"];
                //名前とコメントが入力されたとき
                if(!empty($name && $comment)) {
                    //編集機能が働いたとき
                    if(!empty($_POST["he_id"])) {
                        //変数定義
                        $he_id=$_POST["he_id"];
                        //(SQL)テーブル「tbtest」内でid=:idのデータを編集
                        $sql='UPDATE tbtest SET name=:name, comment=:comment, date=:date, pw=:pw WHERE id=:id';
                        //(SQLセット)
                        $stmt=$pdo->prepare($sql);
                        //値の参照を受け取る(パラメーターセット)
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->bindParam(':pw', $pw1, PDO::PARAM_STR); 
                        $stmt->bindParam(':id', $he_id, PDO::PARAM_INT);
                        //バインド確定(SQL実行)
                        $stmt->execute();
                        $he_id="";
                    //新規投稿のとき
                    } else {
                        //(SQL)列名とデータの組合せ指定しデータ入力
                        $sql='INSERT INTO tbtest(name, comment, date, pw) VALUES(:name, :comment, :date, :pw)';
                        //(SQLセット)
                        $stmt=$pdo->prepare($sql);
                        //値の参照を受け取る(パラメーターセット)
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->bindParam(':pw', $pw1, PDO::PARAM_STR);
                        //バインド確定(SQL実行)
                        $stmt->execute();
                    }
                }                       
            }
            
            
            //*****削除機能*****
            
            //削除ボタンが押されたとき以下を実行する
            if(isset($_POST["deliete"])) {
                //変数定義
                $d_id=$_POST["d_id"];
                $pw2=$_POST["pw2"];  
                //(SQL)テーブル「tbtest」からid=:id,pw=:pwのデータ削除   
                $sql='DELETE FROM tbtest WHERE id=:id AND pw=:pw';
                //(SQLセット)
                $stmt=$pdo->prepare($sql);
                //値の参照を受け取る(パラメーターセット)
                $stmt->bindParam(':id', $d_id, PDO::PARAM_INT);
                $stmt->bindParam(':pw', $pw2, PDO::PARAM_STR);
                //バインド確定(SQL実行)
                $stmt->execute();
            }  
            
            
            //*****編集選択機能*****

            //編集ボタンが押されたとき以下を実行する
            if(isset($_POST["edit"])) {
                //変数定義
                $e_id=$_POST["e_id"];
                $pw3=$_POST["pw3"];
                //(SQL)テーブル「tbtest」からid=:id,pw=:pwのデータ抽出
                $sql='SELECT * FROM tbtest WHERE id=:id AND pw=:pw';
                //(SQLセット) 
                $stmt=$pdo->prepare($sql);
                //値の参照を受け取る(パラメーターセット)
                $stmt->bindParam(':id', $e_id, PDO::PARAM_INT);
                $stmt->bindParam(':pw', $pw3, PDO::PARAM_STR);
                //バインド確定(SQL実行)
                $stmt->execute();
                //該当データ1行を返す
                $results=$stmt->fetch(PDO::FETCH_NUM);
                //変数定義
                $he_id=$results[0];
                $e_name=$results[1];
                $e_comment=$results[2];
                $e_pw=$results[4];
            }
            
        ?>
        
        
        <!-- 投稿フォーム -->
        
        <!-- 編集機能が働いたとき編集前データを表示する -->
        <form action="" method="post">
            <input type="str" name="name" placeholder="名前"
                   value="<?php 
                              if(!empty($e_name)) {
                                    echo $e_name;
                              }
                          ?>"><br>
            <input type="text" name="comment" placeholder="コメント"
                   value="<?php 
                              if(!empty($e_comment)) {
                                  echo $e_comment;
                              }
                          ?>"><br>
            <input type="text" name="pw1" placeholder="パスワード"
                   value="<?php
                              if(!empty($e_pw)) {
                                  echo $e_pw;
                              }
                          ?>">
            <input type="hidden" name="he_id"
                   value="<?php
                              if(!empty($he_id)) {
                                  echo $he_id;
                              }
                          ?>">
            <input type="submit" name="submit"><br>
        </form>
        <br>
        
        
        <!-- 削除フォーム -->
    
        <form action="" method="post">
            <input type="num" name="d_id" placeholder="削除対象番号"><br>
            <input type="text" name="pw2" placeholder="パスワード">
            <input type="submit" name="deliete" value="削除"><br>
        </form>
        <br>
    
        
        <!-- 編集フォーム -->
    
        <form action="" method="post">
            <input type="num" name="e_id" placeholder="編集対象番号"><br>
            <input type="text" name="pw3" placeholder="パスワード">
            <input type="submit" name="edit" value="編集">
        </form>
        <br>

        
        <?php
        
            //*****データ抽出＆表示*****

            //(SQL)テーブル「tbtest」から全データ抽出
            $sql='SELECT * FROM tbtest';
            //(SQL一括実行) 
            $stmt=$pdo->query($sql);
            //該当データ全行を返す
            $results=$stmt->fetchAll();
            //一行ずつ繰り返す
            foreach($results as $row) {
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['date'].',';
                echo $row['pw'].'<br>';
            }
            echo "<hr>";

        ?>
        
        
    </body>
</html>