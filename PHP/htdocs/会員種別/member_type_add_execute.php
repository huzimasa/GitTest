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
    $type_name = trim($_POST['type_name']); // 前後の空白を除去
    $notes = $_POST['notes'];

    try {
        // 既存の会員種別をチェック
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM member_types WHERE type_name = :type_name AND delete_flg= 0");
        $stmt->execute([
            ':type_name' => $type_name
        ]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // 既に登録されている場合、エラーメッセージを表示
            header('Location: member_type_list.php?error=duplicate');
            exit();
        }

        // 新しい会員種別を登録
        $stmt = $pdo->prepare("INSERT INTO member_types (type_name, notes)
                                VALUES (:type_name, :notes)");
        $stmt->execute([
            ':type_name' => $type_name,
            ':notes' => $notes
        ]);

        // 登録成功時
        header('Location: member_type_list.php?success=type_add');
        exit();
    } catch (PDOException $e) {
        // エラー発生時
        echo "エラー: " . $e->getMessage();
        exit();
    }
}
?>
