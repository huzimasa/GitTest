<?php
require '../config/DB_access.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_id = $_POST['type_id'] ?? null;
    $type_name = $_POST['type_name'] ?? null;
    $notes = $_POST['notes'] ?? null;
    
    
    // バリデーション
    if ($type_id === null || !ctype_digit($type_id) || empty($type_name)) {
        header('Location: member_type_list.php?error=invalid_data');
        exit();
    }
    
    try {
        // データを更新
        $stmt = $pdo->prepare("UPDATE member_types SET type_name = :type_name ,notes = :notes WHERE type_id = :type_id");
        $stmt->execute([
            ':type_name' => $type_name,
            ':type_id' => $type_id,
            ':notes' => $notes,
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