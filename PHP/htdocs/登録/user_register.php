<?php
require '../config/DB_access.php';
// POSTデータを受け取り、初期値を設定
$form_data = $_POST ?? [];
try {
    // 会員種別データを取得
    $stmt = $pdo->prepare("SELECT type_id, type_name 
                            FROM member_types 
                            WHERE delete_flg = 0
                            ORDER BY type_id ASC");
    $stmt->execute();
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>会員登録</title>
        <link rel="stylesheet" href="../assets/styles.css">
    </head>
    <body>
    
    	<h1>新規会員登録</h1>
    	<a href="../index.html" class="button">トップへ戻る</a>
    	<a href="../会員一覧/member_list.php" class="button">一覧へ戻る</a>
    	<br>
    	<br>
    
    	<!-- エラーメッセージ表示領域 -->
    	<div id="error-messages"></div>
    	<form id="registrationForm" onsubmit="return validateForm()"
    		action="register_confirm.php" method="post">
    
    		<table>
    
    			<tr>
    				<th>会員氏名 <span class="required">*</span></th>
    				<td><input maxlength="100" type="text" id="name" name="name"
    					class="name-column" placeholder="会員太郎"
    					value="<?php echo htmlspecialchars($form_data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
    					required></td>
    			</tr>
    
    			<tr>
    				<th>住所 <span class="required">*</span></th>
    				<td><input maxlength="255" type="text" id="address" name="address"
    					placeholder="○○県○○市○○区"
    					value="<?php echo htmlspecialchars($form_data['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
    					required></td>
    			</tr>
    
    			<tr>
    				<th>電話番号（固定）</th>
    				<td><input pattern="\d{2,4}-\d{2,4}-\d{2,4}" type="text"
    					id="landline_phone" name="landline_phone"
    					placeholder="0123-456-7890"
    					value="<?php echo htmlspecialchars($form_data['landline_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    
    			<tr>
    				<th>電話番号（携帯）</th>
    				<td><input pattern="\d{2,4}-\d{2,4}-\d{2,4}" type="text"
    					id="mobile_phone" name="mobile_phone" placeholder="012-3456-7890"
    					value="<?php echo htmlspecialchars($form_data['mobile_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
    			</tr>
    
    			<tr>
    				<th>メールアドレス <span class="required">*</span></th>
    				<td><input maxlength="100" type="email" id="email" name="email"
    					placeholder="abcd@wizway.co.jp"
    					value="<?php echo htmlspecialchars($form_data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
    					required></td>
    			</tr>
    
    			<tr>
    				<th>入会日 <span class="required">*</span></th>
    				<td><input type="date" id="join_date" name="join_date"
    					value="<?php echo htmlspecialchars($form_data['join_date'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8'); ?>"
    					required></td>
    			</tr>
    
    			<tr>
    				<th>会員種別</th>
    				<td><select id="member_type_id" name="member_type_id"
    					class="member-type-select" required>
    						<option value="">選択してください</option>
                	<?php foreach ($member_types as $type): ?>
                    <option
    							value="<?php echo htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8'); ?>"
    							<?php echo (isset($form_data['member_type_id']) && $form_data['member_type_id'] == $type['type_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                	<?php endforeach; ?>
            		</select></td>
    			</tr>
    
    			<tr>
    				<th>備考</th>
    				<td><textarea id="notes" name="notes" rows="4" maxlength="100"
    						placeholder="改行含めて100文字まで"><?php echo htmlspecialchars($form_data['notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></td>
    			</tr>
    		</table>
    
    		<div>
    			<button type="submit" class="button">登録する</button>
    			<a href="user_register.php" type="reset" class="button">リセット</a>
    		</div>
    	</form>
    	
    	
    	<script src="/assets/validation.js" defer></script>
    </body>
</html>
