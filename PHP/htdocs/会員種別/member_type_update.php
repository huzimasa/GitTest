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
    $type_id = $_POST['type_id'] ?? null;
    $type_name = trim($_POST['type_name']) ?? null;
    $notes = $_POST['notes'] ?? null;

    try {
        // 現在のデータを取得
        $stmt = $pdo->prepare("SELECT type_name FROM member_types WHERE type_id = :type_id");
        $stmt->execute([
            ':type_id' => $type_id
        ]);
        $current_type_name = $stmt->fetchColumn();

        // `type_name` が変更された場合のみ重複チェック
        if ($type_name !== $current_type_name) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM member_types WHERE type_name = :type_name AND type_id != :type_id AND delete_flg= 0");
            $stmt->execute([
                ':type_name' => $type_name,
                ':type_id' => $type_id
            ]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // 既に存在する場合、エラーメッセージ付きでリダイレクト
                header('Location: member_type_list.php?error=duplicate');
                exit();
            }
        }

        // データを更新
        $stmt = $pdo->prepare("UPDATE member_types SET type_name = :type_name, notes = :notes WHERE type_id = :type_id");
        $stmt->execute([
            ':type_name' => $type_name,
            ':type_id' => $type_id,
            ':notes' => $notes
        ]);

        // 更新成功時
        header('Location: member_type_list.php?success=type_updated');
        exit();
    } catch (PDOException $e) {
        // エラー発生時
        echo "エラー: " . $e->getMessage();
        exit();
    }
} else {
    // 不正なアクセスを防止
    header('Location: member_type_list.php');
    exit();
}
?>
