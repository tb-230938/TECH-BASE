<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission5-1</title>
        <style>
            body{
                background-color:lavenderblush;
                text-align:center;
            }
            
            h1, b{
                color:dimgray;
            }
            
            p{
                color:#333333;
            }
        </style>
    </head>

<!--*******************************************************************************************************-->
    
    <body>
        <h1>みんなのひとこと💬</h1>

//************************************************************************************************************
//初期設定
            error_reporting(E_ALL & ~E_NOTICE);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $del = $_POST["del"];
            $edit = $_POST["edit"];
            $edit_data = $_POST["edit_data"];
            $pass1 = $_POST["pass1"];
            $pass2 = $_POST["pass2"];
            $pass3 = $_POST["pass3"];
            $time = date ("Y/m/d/ H:i:s");

//************************************************************************************************************
//データベース接続            
            //$dsnの式の中にスペースを入れないこと！
            // 【サンプル】
            // ・データベース名：
            // ・ユーザー名：
            // ・パスワード：
            // の学生の場合：

            // DB接続設定
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//            echo "#0<hr>";

//************************************************************************************************************
//テーブル作成
            $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "time DATETIME,"
            . "pass1 TEXT"
            . ");";
            $stmt = $pdo->query($sql);
//            echo "#1<hr>";

//************************************************************************************************************
//テーブル一覧
            $sql ='SHOW TABLES';
            $result = $pdo -> query($sql);
            foreach ($result as $row){
//                echo $row[0];
//                echo '<br>';
            }
//            echo "#2<hr>";

//************************************************************************************************************
//テーブル構成詳細
            $sql ='SHOW CREATE TABLE mission5_1';
            $result = $pdo -> query($sql);
            foreach ($result as $row){
//                echo $row[1];
            }
//            echo "<br>#3<hr>";

//************************************************************************************************************
//レコード編集
            if (!empty($name && $comment && $pass1 && $edit_data)){
                $id = $edit_data;
                $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,pass1=:pass1 WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                echo "投稿番号：". $edit_data. " のデータの編集が完了しました。<br>";
                $edit_data = "";
//                echo "#5-3<hr>";
            }

//************************************************************************************************************
//レコード挿入
            elseif (!empty($name && $comment && $pass1) && empty($edit_data)){
                $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, time, pass1) VALUES (:name, :comment, :time, :pass1)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':time', $time, PDO::PARAM_STR);
                $sql -> bindParam(':pass1', $pass1, PDO::PARAM_STR);
                $time = date("Y/m/d H:i:s");
                $sql -> execute();
                echo "新規投稿が完了しました。";
                //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
//                echo "#5-1<hr>";
            }

//************************************************************************************************************
//レコード削除
            elseif (!empty ($del && $pass2)){
                $sql = 'SELECT * FROM mission5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    if ($row['id'] == $del){
                        if ($pass2 == $row['pass1']){
                            $id = $del;
                            $sql = 'delete from mission5_1 where id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                            echo "投稿番号：". $del. " の削除が完了しました。";
                        }else{
                            echo "パスワードが一致しません。<br>";
                        }
                    }/*else{
                        echo "何もしませんでした<br>";
                    }*/
                }
//                echo "#5-2<hr>";
            }
            
//************************************************************************************************************
//正しく入力されなかった時
            /*else{
                echo "正しく入力されませんでした。<br>";
                echo "もう一度最初からやり直してください。<br>";
            }*/

//************************************************************************************************************
//編集前段階
            if (!empty($edit && $pass3)){
                $sql = 'SELECT * FROM mission5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    if ($row['id'] == $edit){
                        if ($pass3 == $row['pass1']){
                            echo "投稿番号：". $edit. " を編集をします<br>";
                            echo "新規投稿フォームに表示されている内容を書き換えたらボタンを押してください。";
                            $name_data = $row['name'];
                            $comment_data = $row['comment'];
                            $pass1_data = $row['pass1'];
                            $edit_data = $edit;
                        }else{
                            echo "パスワードが一致しません。<br>";
                        }
                    }/*else{
                        echo "何もしませんでした<br>";
                    }*/
                }
//                echo "#5-3<hr>";
            }
        ?>

<!--*******************************************************************************************************-->
<!--フォーム作成-->
        <form action = "" method = "post">
            <p><b>新規投稿</b><br>
            <small>名前、投稿内容、パスワードを設定してボタンを押してください。</small><br>
            <input type = "text" name = "name" placeholder = "名前" value="<?php if(!empty($name_data)){echo $name_data;} ?>">
            <input type = "text" name = "comment" placeholder = "投稿" value="<?php if(!empty($comment_data)){echo $comment_data;} ?>">
            <input type = "password" name = "pass1" placeholder = "パスワード" value="<?php if(!empty($pass1_data)){echo $pass1_data;} ?>">
            <input type="hidden" name="edit_data" placeholder="編集対象番号2" value="<?php if(!empty($edit_data)){echo $edit_data;} ?>">
            <input type = "submit" name = "submit" value="<?php if(!empty($edit_data)){echo "編集完了";}else{echo "送信";} ?>"></p>
            
            <p><b>削除</b></br>
            <small>削除したい投稿番号とパスワードを入力してボタンを押してください。</small><br>
            <input type = "number" name = "del" placeholder = "削除対象番号">
            <input type = "password" name = "pass2" placeholder = "パスワード">
            <input type = "submit" name = "削除" value = "削除"></p>
            
            <p><b>編集</b><br>
            <small>編集したい投稿番号とパスワードを入力してボタンを押してください。</small><br>
            <input type = "number" name = "edit" placeholder = "編集対象番号">
            <input type = "password" name = "pass3" placeholder = "パスワード">
            <input type = "submit" name = "編集" value = "編集"></p>
        </form>
        <hr>

<!--*******************************************************************************************************-->
<!--レコード表示-->
        <?php
            //$rowの添字（[ ]内）は、4-2で作成したカラムの名称に併せる必要があります。
            $sql = 'SELECT * FROM mission5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].'：';
                echo $row['name'].'さん  ...  ';
                echo $row['comment'].'  ';
                echo  '<fontcolor = "gray">（'。$ row [ 'time' ]。'）<br> </ font>' ;
//                エコー '<fontcolor = "gray"> <small>設定パスワード：'。$ row [ 'pass1' ]。'</ small> </ font> <br>' ;
            }
//            echo "#6<hr>";
        ?>
    </body>
</html>
