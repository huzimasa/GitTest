<?php
require_once '../config/csrf_token.php';
// トークン生成
$csrf_token = generate_csrf_token();

require '../config/DB_access.php';

$type_id = $_GET['type_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM member_types WHERE type_id = :type_id");
    $stmt->execute([
        ':type_id' => $type_id
    ]);
    $type = $stmt->fetch(PDO::FETCH_ASSOC);
    if (! $type) {
        throw new Exception("データが見つかりません");
    }
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
	    <meta charset="UTF-8">
    	<title>会員種別編集</title>
    	<link rel="stylesheet" href="../assets/styles.css">
	</head>
	<body>
		<h1>会員種別編集</h1>
    	<?php if (isset($_GET['error'])): ?>
            <p style="color: red;">
            <?php switch ($_GET['error']) {
                case 'invalid_id':
                    echo '無効なIDが指定されました。';
                    break;
                case 'duplicate':
                    echo 'この会員種別は既に登録されています。';
                    break;
                default:
                    echo '不明なエラーが発生しました。';
            }
            ?>
            </p>
    	<?php endif; ?>
    
    	<?php if (isset($_GET['success']) && $_GET['success'] === 'type_updated'): ?>
        	<p style="color: green;">会員種別が正常に更新されました。</p>
    	<?php endif; ?>
    	
    	<!-- js制御のエラー表示場所 -->
    	<div id="error-messages"></div>
    	
    	<form id="memberTypeForm" action="member_type_update.php" method="post">
    		<input type="hidden" name="csrf_token"
    			value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">	
			<input type="hidden" name="type_id" id="type_id"
				value="<?= htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8') ?>">
			
			<table>
    			<tr>
    				<th>種別名</th>
    				<td>
    					<input maxlength='10' id="type_name" name="type_name" type="text"
							value="<?= htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8') ?>" required>
					</td>
    			</tr>
    			
    			<tr>
    				<th>備考</th>
    				<td>
    					<textarea name="notes" id="notes" rows="4" maxlength='100'><?php echo htmlspecialchars($type['notes'] ?? "", ENT_QUOTES, 'UTF-8'); ?></textarea>
    				</td>
    			</tr>
    		</table>

			<button type="submit" onclick="return confirm('この内容で変更しますか？');"
		   		class="button">変更</button>
			<a href="member_type_detail.php?id=<?php echo htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8'); ?>" class="button" class="button">戻る</a>
		</form>
		
		
		<script src="../assets/validation.js"></script>
	</body>
</html>
