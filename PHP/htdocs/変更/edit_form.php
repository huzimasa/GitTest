<?php
require '../config/DB_access.php';

// 遷移元を取得（GET か POST から取得）
$source = $_GET['source'] ?? $_POST['source'] ?? '';
// 会員IDをGETで取得
$id = $_GET['id'] ?? '';

// 会員種別データを取得
try {
    $stmt = $pdo->prepare("SELECT type_id, type_name FROM member_types WHERE delete_flg = 0 ORDER BY type_id ASC");
    $stmt->execute();
    $member_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "エラーが発生しました。<a href='member_list.php'>一覧へ戻る</a>";
    exit();
}

// 詳細画面からの遷移（GET: idを取得し、DBから情報を取得）
if ($source === 'detail') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $form_data = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "エラーが発生しました。<a href='member_list.php'>一覧へ戻る</a>";
        exit();
    }
} elseif ($source === 'confirm') { // 確認画面からの遷移（POST）
    $form_data = $_POST;
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>会員編集</title>
        <link rel="stylesheet" href="../assets/styles.css">
    </head>
    <body>
    
    	<h1>会員編集</h1>
    	<a href="../index.html" class="button">トップへ戻る</a>
    	<a href="../会員一覧/member_list.php" class="button">一覧へ戻る</a>
    
    	<form id="registrationForm" method="post"
    		action="../変更/update_confirm.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
    		<table>
    			<tr>
    				<th>会員氏名</th>
    				<td><input type="text" name="name" maxlength='100' id="name"
    					required
    					value="<?php echo htmlspecialchars($form_data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    			<tr>
    				<th>住所</th>
    				<td><input type="text" name="address" maxlength='255' id="address"
    					required
    					value="<?php echo htmlspecialchars($form_data['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    			<tr>
    				<th>電話番号（固定）</th>
    				<td><input type="text" name="landline_phone" id="landline_phone"
    					pattern="\d{1,4}-\d{1,4}-\d{1,4}"
    					value="<?php echo htmlspecialchars($form_data['landline_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    			<tr>
    				<th>電話番号（携帯）</th>
    				<td><input type="text" name="mobile_phone" id="mobile_phone"
    					pattern="\d{1,4}-\d{1,4}-\d{1,4}"
    					value="<?php echo htmlspecialchars($form_data['mobile_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    			<tr>
    				<th>メールアドレス</th>
    				<td><input type="email" name="email" id="email" maxlength='100'
    					required
    					value="<?php echo htmlspecialchars($form_data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    			<tr>
    				<th>入会日</th>
    				<td><input type="date" name="join_date" id="join_date" required
    					value="<?php echo htmlspecialchars($form_data['join_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    			<tr>
    				<th>会員種別</th>
    				<td>
    					<select id="member_type_id" name="member_type_id" required>
    						<option value="">選択してください</option>
                            <?php foreach ($member_types as $type): ?>
                                <option value="<?php echo htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8'); ?>"
    							<?php echo (isset($form_data['member_type_id']) && $form_data['member_type_id'] == $type['type_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
    			</tr>
    			<tr>
    				<th>備考</th>
    				<td><textarea name="notes" id="notes" rows="4" maxlength='100'><?php echo htmlspecialchars($form_data['notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></td>
    			</tr>
    		</table>
    
    		<div class="button-container">
    			<!-- 更新ボタン -->
    			<button type="submit" class="button">更新する</button>
    		<a href="../会員詳細/detail.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" class="button">戻る</a>
			</div>
		</form>
		
		
		<script src="../assets/validation.js"></script>
	</body>
</html>
