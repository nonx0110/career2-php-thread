<html>
<head><title>掲示板</title></head>
<body>
<font color="red">
    <center>
        <h1>掲示板App</h1>
    </center>
</font>
<h2>投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="btn" value="投稿する">
    
</form>

<h2>スレッド</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="hidden" name="method" value="DELETE">
    <button type="submit">スレッドの全削除</button>
</form>

<?php

const THREAD_FILE = 'thread.txt';

function threadInit(){
    file_put_contents(THREAD_FILE,"");
    //echo '実行';
}

function readData() {
    // ファイルが存在しなければデフォルト空文字のファイルを作成する
    if (! file_exists(THREAD_FILE)) {
        $fp = fopen(THREAD_FILE, 'w');
        fwrite($fp, '');
        fclose($fp);
    }

    $thread_text = file_get_contents(THREAD_FILE);
    //echo '実行';
    echo $thread_text;
}

function writeData() {
    $personal_name = $_POST['personal_name'];
    $contents = $_POST['contents'];
    $contents = nl2br($contents);
    $day = date("Y/m/d");
    $time = date(" H:i:s");
    $week_Data = date("w");
    $week = ["日","月","火","水","木","土"];

    $data = "<hr>\n";
    $data = $data."<p>投稿者:".$personal_name.":".$day."(".$week[$week_Data].")".$time."</p>\n";
    $data = $data."<p>内容:</p>\n";
    $data = $data."<p>".$contents."</p>\n";

    $fp = fopen(THREAD_FILE, 'a');

    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp,  $data) === FALSE){
                print('ファイル書き込みに失敗しました');
            }

            flock($fp, LOCK_UN);
        }else{
            print('ファイルロックに失敗しました');
        }

    }

    fclose($fp);

    // ブラウザのリロード対策
    // 11:06
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
    // sample commen
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["method"]) && $_POST["method"] === "DELETE"){
        threadInit();
    }else{
        writeData();
    }
}

readData();

?>

</body>
</html>