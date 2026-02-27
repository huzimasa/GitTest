<?php
require '../config/DB_access.php';
// 会員IDの取得(戻るときはPOST)
$id = $_GET['id']??$_POST['id']??'';
if (! $id) {
    echo "会員IDが指定されていません。<a href='../会員一覧/member_list.php'>一覧へ戻る</a>";
    exit();
}
// データベースから会員情報を取得
try {
    $stmt = $pdo->prepare("SELECT m.*, t.type_name
                           FROM members m
                           INNER JOIN member_types t
                           ON m.member_type_id = t.type_id
                           WHERE m.member_id = :id");
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
			<a href="../index.html" class="button">トップへ戻る</a>
			<a href="../登録/user_register.php" class="button">会員登録画面</a>
			<a href="../会員一覧/member_list.php" class="button">一覧へ戻る</a><br>
		<form method="post" action="../変更/edit_form.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>
				&source=detail">
		
		    <!-- 会員情報の表示 -->
			<table>
    
    			<tr>
    				<th>会員ID</th>
    				<td><?php echo htmlspecialchars($member['member_id'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>会員氏名</th>
    				<td><?php echo htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>住所</th>
    				<td><?php echo htmlspecialchars($member['address'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>電話番号（固定）</th>
    				<td><?php echo htmlspecialchars($member['landline_phone'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>電話番号（携帯）</th>
    				<td><?php echo htmlspecialchars($member['mobile_phone'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>メールアドレス</th>
    				<td><?php echo htmlspecialchars($member['email'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>入会日</th>
    				<td><?php echo htmlspecialchars($member['join_date'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>会員種別</th>
    				<td><?php echo htmlspecialchars($member['type_name'], ENT_QUOTES, 'UTF-8') ?></td>
    			</tr>
    
    			<tr>
    				<th>備考</th>
    				<td><?php echo htmlspecialchars($member['notes'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>登録日時</th>
    				<td><?php echo htmlspecialchars($member['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>
    
    			<tr>
    				<th>更新日時</th>
    				<td><?php echo htmlspecialchars($member['update_at'], ENT_QUOTES, 'UTF-8'); ?></td>
    			</tr>

		</table>
		  <!-- 編集ボタン -->
			<button type="submit" class="button">編集する</button>

		  <!-- 削除リンク -->
			<a href="../削除/delete_execute.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>"
				onclick="return confirm('本当に削除しますか？');" class="button">削除する </a>
		</form>
	</body>
</html>
