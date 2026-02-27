<?php
require_once '../config/csrf_token.php';
// フォームデータを処理する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    // CSRFトークンの検証
    if (! verify_csrf_token($csrf_token)) {
        // トークンが無効の場合はエラーメッセージを表示して処理を停止
        http_response_code(403);
        echo 'CSRFトークンが無効です。再度お試しください。';
        exit();
    }
}

require '../config/DB_access.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $landline_phone = $_POST['landline_phone'];
    $mobile_phone = $_POST['mobile_phone'];
    $email = $_POST['email'];
    $notes = $_POST['notes'];
    $member_type_id = $_POST['member_type_id']; // 会員種別を追加

    try {
        $stmt = $pdo->prepare("
            UPDATE members
            SET name = :name, address = :address, landline_phone = :landline_phone,
                mobile_phone = :mobile_phone, email = :email, notes = :notes, member_type_id = :member_type_id
            WHERE member_id = :id
        ");
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':landline_phone' => $landline_phone,
            ':mobile_phone' => $mobile_phone,
            ':email' => $email,
            ':notes' => $notes,
            ':member_type_id' => $member_type_id, // 会員種別の値を追加
            ':id' => $id
        ]);

        header('Location: ../変更/update_complete.php');
        exit();
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    header('Location: member_list.php');
    exit();
}
?>

