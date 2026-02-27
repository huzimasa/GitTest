<?php
require_once '../config/csrf_token.php';
// トークン生成
$csrf_token = generate_csrf_token();

require '../config/DB_access.php';
    
$form_data = $_POST ??'';
$id = $_GET['id'] ??'';

//会員種別名の取得
try {
    $stmt = $pdo->prepare("SELECT type_name FROM member_types WHERE delete_flg = 0 AND type_id = :type_id");
    $stmt->bindValue(':type_id', $form_data['member_type_id'], PDO::PARAM_INT);
    $stmt->execute();
    $member_type = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($member_type) {
        $form_data['type_name'] = $member_type['type_name'];
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
    	<meta charset="UTF-8">
    	<title>更新内容確認</title>
    	<link rel="stylesheet" href="../assets/styles.css">
    </head>
	<body>
    	<h1>更新内容確認</h1>
    	<p>以下の内容で更新します。よろしいですか？</p>
    	<table>
    		<tr>
    			<th>会員氏名</th>
    			<td><?php echo htmlspecialchars($form_data['name'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    		<tr>
    			<th>住所</th>
    			<td><?php echo htmlspecialchars($form_data['address'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    		<tr>
    			<th>電話番号（固定）</th>
    			<td><?php echo htmlspecialchars($form_data['landline_phone'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    		<tr>
    			<th>電話番号（携帯）</th>
    			<td><?php echo htmlspecialchars($form_data['mobile_phone'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    		<tr>
    			<th>メールアドレス</th>
    			<td><?php echo htmlspecialchars($form_data['email'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    		<tr>
    			<th>入会日</th>
    			<td><?php echo htmlspecialchars($form_data['join_date'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    		<tr>
    			<th>会員種別</th>
    			<td><?php echo htmlspecialchars($form_data['type_name'], ENT_QUOTES, 'UTF-8') ?></td>
    		</tr>
    		<tr>
    			<th>備考</th>
    			<td><?php echo htmlspecialchars($form_data['notes'], ENT_QUOTES, 'UTF-8'); ?></td>
    		</tr>
    	</table>
    
    	<div class="button-container">
    		<!-- 確定ボタン -->
        	<form method="post" action="update_execute.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
            	<?php foreach ($form_data as $key => $value): ?>
                    <input type="hidden"
                        name="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>"
                        value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endforeach; ?>
            	<input type="hidden" name="csrf_token"
                	value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
            	<button type="submit" class="button">確定</button>
        	</form>
    
    		<!-- 戻るボタン -->    		
            <form method="post" action="edit_form.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>&source=confirm">
                <?php foreach ($form_data as $key => $value): ?>
                    <input type="hidden"
                        name="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>"
                        value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endforeach; ?>
            	<button type="submit" class="button">戻る</button>
            </form>
    	</div>
	</body>
</html>
