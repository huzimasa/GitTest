<?php
require_once '../config/csrf_token.php';
// トークン生成
$csrf_token = generate_csrf_token();

require '../config/DB_access.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 削除対象の type_id を取得
    $id = $_POST['type_id'] ?? null;
}
//戻る時はGET
$id = $_POST['id'] ?? $_GET['id']?? '';

// データベースから会員情報を取得
try {
    $stmt = $pdo->prepare("SELECT * FROM member_types WHERE type_id = :id");
    $stmt->execute([
        ':id' => $id
    ]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    <title>会員詳細</title>
    <link rel="stylesheet" href="../assets/styles.css">
    </head>
    <body>
    	<h1>会員詳細</h1>
    	<?php if (isset($_GET['error'])): ?>
        <p style="color: red;">
            <?php
        switch ($_GET['error']) {
            case 'invalid_id':
                echo '無効なIDが指定されました。';
                break;
            case 'type_in_use':
                echo 'この種別は使用されているため削除できません。';
                break;
            case 'delete_failed':
                echo '削除処理中にエラーが発生しました。';
                break;
            default:
                echo '不明なエラーが発生しました。';
        }
        ?>
        </p>
        <?php endif; ?>
    	<a href="../index.html" class="button">トップへ戻る</a>
    	<a href="member_type_list.php" class="button">一覧へ戻る</a>
    	<br>
    
    	<!-- 会員情報の表示 -->
    	<table border="1" style="border-collapse: collapse; width: 100%;">
    		<tr>
    			<th style="padding: 8px;">会員ID</th>
    			<td style="padding: 8px;"><?php echo htmlspecialchars($member['type_id'] ?? "", ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    
    		<tr>
    			<th style="padding: 8px;">会員種別</th>
    			<td style="padding: 8px;"><?php echo htmlspecialchars($member['type_name']?? "",  ENT_QUOTES, 'UTF-8') ?></td>
    		</tr>
    		<tr>
    			<th style="padding: 8px;">備考</th>
    			<td style="padding: 8px; white-space: break-spaces;"><?php echo htmlspecialchars($member['notes'] ?? "" , ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    
    	</table>
    	<br>
    	
    	<form method="post" action="member_type_edit.php">
    		<input type="hidden" name="csrf_token"
    			value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
    
    	<!-- 編集リンク -->
    	<a href="member_type_edit.php?type_id=<?= htmlspecialchars($member['type_id'], ENT_QUOTES, 'UTF-8') ?>"
    		class="button">編集</a>
    
    	<!-- 削除リンク -->
    	<a href="member_type_delete.php?type_id=<?= htmlspecialchars($member['type_id'], ENT_QUOTES, 'UTF-8') ?>"
    		onclick="return confirm('本当に削除しますか？');" class="button">削除</a>
    	</form>
    </body>
</html>