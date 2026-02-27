<?php
require_once '../config/csrf_token.php';
// トークン生成
$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
    	<meta charset="UTF-8">
    	<title>会員種別追加</title>
    	<link rel="stylesheet" href="../assets/styles.css">
    </head>
	<body>
    	<h1>会員種別追加</h1>
    	<form id="memberTypeForm" action="member_type_add_execute.php" method="post">
    	
    	<!-- js制御のエラー表示場所 -->
    	<div id="error-messages"></div>
    	
    		<table>
    			<tr>
    				<th>種別名<span class="required">*</span></th>
    				<td><input id="type_name" name="type_name" maxlength='10' required></td>
    			</tr>
    			
    			<tr>
    				<th>備考</th>
    				<td><textarea id="notes" name="notes" rows="4" maxlength='100'></textarea>
    			</tr>
    		</table>
    	
    		<input type="hidden" name="csrf_token"
            	value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
        
        	<div class="button-container">
        		<button type="submit" onclick="return confirm('この名称で追加しますか？');" class="button">追加</button>
    			<a href="member_type_list.php" class="button">戻る</a>
    		</div>
    	</form>
    	<script src="../assets/validation.js"></script>
	</body>
</html>
