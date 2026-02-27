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

// POSTデータを受け取る
$form_data = $_POST ?? [];

try {
    $stmt = $pdo->prepare("
        INSERT INTO members (name, address, landline_phone, mobile_phone, email, join_date, member_type_id, notes)
        VALUES (:name, :address, :landline_phone, :mobile_phone, :email, :join_date, :member_type_id, :notes)
    ");
    $stmt->execute([
        ':name' => $form_data['name'],
        ':address' => $form_data['address'],
        ':landline_phone' => $form_data['landline_phone'] ?? null,
        ':mobile_phone' => $form_data['mobile_phone'] ?? null,
        ':email' => $form_data['email'],
        ':join_date' => $form_data['join_date'],
        ':member_type_id' => $form_data['member_type_id'],
        ':notes' => $form_data['notes'] ?? null
    ]);
    header('Location: ../登録/register_complete.php');
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
