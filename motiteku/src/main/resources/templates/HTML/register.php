<?php
// 1. データベースへの接続設定（学校のMariaDBに繋ぎます）
$host = 'localhost';
$dbname = 'motiteku';
$username = 'root';
$password = 'mysql'; // ★学校で決まっているパスワードがあればここに入力（空ならこのままでOK）

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. HTMLのフォームから送られてきたデータを受け取る
    $user_name = $_POST['username'] ?? '';
    $birthday  = $_POST['birthday'] ?? '';
    $gender    = $_POST['gender'] ?? '';
    $work      = $_POST['work'] ?? '';
    
    // 地域（都道府県 + 市）を合体させて1つの住所にする
    $prefecture = $_POST['prefecture'] ?? '';
    $city       = $_POST['city'] ?? '';
    $address    = $prefecture . $city;

    // useridは今回は仮でランダムなIDを自動生成します
    $user_id = 'user_' . uniqid(); 

    // 3. データベースに保存するSQL文を作る
    $sql = "INSERT INTO userinfo (userid, username, birthday, gender, address, work) 
            VALUES (:userid, :username, :birthday, :gender, :address, :work)";
    
    $stmt = $pdo->prepare($sql);

    // 4. 安全にデータをバインド（割り当て）して実行する
    $stmt->execute([
        ':userid'   => $user_id,
        ':username' => $user_name,
        ':birthday' => $birthday,
        ':gender'   => $gender,
        ':address'  => $address,
        ':work'     => $work
    ]);

    // 5. 登録完了したら一覧画面やホーム画面に飛ばす
    echo "<script>alert('ユーザー情報の登録が完了しました！'); location.href='index.html';</script>";

} catch (PDOException $e) {
    // エラーが発生した場合は画面に表示する
    die("データベースエラー: " . $e->getMessage());
}
?>