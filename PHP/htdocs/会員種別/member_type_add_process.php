<?php
require '../config/DB_access.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_name = $_POST['type_name'] ?? null;
    $notes = $_POST['notes'];
}

try {
    $stmt = $pdo->prepare("INSERT INTO member_types (type_name,notes) 
                            VALUES (:type_name, :notes)");
    $stmt->execute([
        ':type_name' => $type_name,
        ':notes' => $notes
    ]);

    // 更新成功時
    header('Location: member_type_list.php?success=type_add');
    exit();
} catch (PDOException $e) {
    // エラー発生時
    echo "エラー: " . $e->getMessage();
    exit();
}
?>