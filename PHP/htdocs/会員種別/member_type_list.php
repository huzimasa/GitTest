<?php
require '../config/DB_access.php';
// 検索条件の取得
$member_type = $_GET['member_type'] ?? '';

// 検索条件を動的に構築
$where = "WHERE delete_flg = 0";
$params = [];

if (! empty($member_type)) {
    $where = "WHERE type_name = :type_id";
    $params[':type_id'] = "$member_type";
}

try {
    // 会員種別データを取得
    $query = "SELECT type_id, type_name FROM member_types $where ORDER BY type_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $member_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>会員種別一覧</title>
        <link rel="stylesheet" href="../assets/styles.css">
    </head>
    <body>
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
            
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'type_deleted'): ?>
            <p style="color: green;">会員種別が正常に削除されました。</p>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'type_updated'): ?>
            <p style="color: green;">会員種別が正常に更新されました。</p>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'type_add'): ?>
            <p style="color: green;">会員種別が正常に登録されました。</p>
	    <?php endif; ?>
	    
	    
    	<h1>会員種別一覧</h1>
	    	<a href="../index.html" class="button">トップへ戻る</a>
	    	<a href="member_type_add.php" class="button">新規種別追加画面</a>
    
    	<!-- 検索フォーム -->
    	<form method="get" action="member_type_list.php">
    		<div class="form-group">
    			<label for="member_type">会員種別:</label>
    		<select name="member_type" id="member_type">
    			<option value="">-- 選択してください --</option>
                    <?php foreach ($member_types as $type): ?>
                        <option value="<?= htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8') ?>"
        					<?= ($member_type == $type['type_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
            </select> <br>
    			<button type="submit" class="button">検索</button>
    			<a href="member_type_list.php" class="button">リセット</a>
    		</div>
    	</form>
    
    	<!-- 検索結果表示 -->
        <?php if (!empty($member_types)): ?>
            <table border="1">
        		<thead>
        			<tr>
        				<th>ID</th>
        				<th>種別名</th>
        				<th>操作</th>
        			</tr>
        		</thead>
        		<tbody>
                    <?php foreach ($member_types as $type): ?>
                        <tr>
            				<td><?= htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8') ?></td>
            				<td><?= htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8') ?></td>
            				<td>
            					<form method="post" action="member_type_detail.php">
            						<input type="hidden" name="id"
            							value="<?php echo htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8'); ?>">
            						<button type="submit" class="button">詳細</button>
            					</form>
            				</td>
        				</tr>
                    <?php endforeach; ?>
                </tbody>
        	</table>
        <?php else: ?>
            <p>条件に一致する会員が見つかりませんでした。</p>
        <?php endif; ?>
    </body>
</html>
