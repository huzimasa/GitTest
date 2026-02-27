<?php
require '../config/DB_access.php';
// 検索条件の取得
$id = $_GET['id'] ?? '';
$name = $_GET['name'] ?? '';
$join_date_from = $_GET['join_date_from'] ?? '';
$join_date_to = $_GET['join_date_to'] ?? '';
$member_type = $_GET['member_type'] ?? '';

// 現在のページ番号
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
// キーセットページング用の最後のID
$last_id = $_GET['last_id'] ?? null;
// キーセットページング用の最初のID
$prev_id = $_GET['prev_id'] ?? null;
// 1ページあたりの表示件数
$limit = 10;
// ------------------------------------------------------------------------------------------------------------------------------

// 検索条件の組み立て
$where = "WHERE m.delete_flg = 0";
$params = [];

// 会員ID 完全一致
if ($id) {
    $where .= " AND m.member_id = :id";
    $params[':id'] = $id;
}
// 会員氏名 前後方一致
if ($name) {
    $where .= " AND m.name LIKE :name";
    $params[':name'] = "%$name%";
}
// 入会日(from) 2025/01/01～
if ($join_date_from) {
    $where .= " AND m.join_date >= :join_date_from";
    $params[':join_date_from'] = $join_date_from;
}
// 入会日(to) ～2025/01/01
if ($join_date_to) {
    $where .= " AND m.join_date <= :join_date_to";
    $params[':join_date_to'] = $join_date_to;
}
// 会員種別(種別ID管理)
if ($member_type) {
    $where .= " AND m.member_type_id = :type_id";
    $params[':type_id'] = $member_type;
}
// 次へボタン(次のページ)の参照キー
if ($last_id) {
    $where .= " AND m.member_id > :last_id";
    $params[':last_id'] = $last_id;
    $order = "ASC";
    // 前へボタン(前のページ)の参照キー
} elseif ($prev_id) {
    $where .= " AND m.member_id < :prev_id";
    $params[':prev_id'] = $prev_id;
    $order = "DESC";
} else {
    $order = "ASC";
}
// ------------------------------------------------------------------------------------------------------------------------------

// table_record_counts から members の総レコード数を取得
$count_query = "SELECT record_count FROM table_record_counts WHERE table_name = 'members'";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();

// 検索ワードの入力チェック用変数
$is_initial_display = empty($id) && empty($name) && empty($join_date_from) && empty($join_date_to) && empty($member_type);
// ------------------------------------------------------------------------------------------------------------------------------

// 初期表示でない場合のみ絞り込み後の件数を取得
if (! $is_initial_display) {

    // membersテーブルのmember_id(会員ID)の分だけCOUNT
    $filtered_count_query = "SELECT COUNT(m.member_id) FROM members m $where";
    $filtered_count_stmt = $pdo->prepare($filtered_count_query);
    foreach ($params as $key => $value) {
        $filtered_count_stmt->bindValue($key, $value);
    }
    $filtered_count_stmt->execute();
    $filtered_total_records = $filtered_count_stmt->fetchColumn();
}
// ------------------------------------------------------------------------------------------------------------------------------

// 絞り込み(検索)後の総ページ数を計算（初期表示時はtable_record_countsの総件数で計算）
$total_pages = ceil(($filtered_total_records ?? $total_records) / $limit);

// 最後のページの場合は降順に、LIMITは端数で表示
if ($page == $total_pages) {
    $order = "DESC";
    // 初期表示の総件数か絞り込み後の総件数を格納
    $use_total_records = $filtered_total_records ?? $total_records;
    // 総件数の計算結果が0以外の場合、ページングに余り分表示させる(最後のページ用)
    if ($use_total_records % $limit !== 0) {
        $limit = $use_total_records % $limit;
    }
}
// ------------------------------------------------------------------------------------------------------------------------------
/*
 * -----------------------------------------
 * 会員ID、会員氏名、入会日、会員種別を検索
 * 会員種別は別テーブルの為、INNER JOIN
 * 会員IDを昇順 OR 降順
 * 10件ずつOR 端数分表示
 * -----------------------------------------
 */

// 会員情報を取得
$members_query = "SELECT m.member_id, m.name, m.join_date, mt.type_name
                FROM members m
                INNER JOIN member_types mt ON m.member_type_id = mt.type_id
                $where
                ORDER BY m.member_id $order
                LIMIT :limit";
$members_stmt = $pdo->prepare($members_query);
foreach ($params as $key => $value) {
    $members_stmt->bindValue($key, $value);
}
$members_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$members_stmt->execute();
$members = $members_stmt->fetchAll(PDO::FETCH_ASSOC);
// ------------------------------------------------------------------------------------------------------------------------------

// 前ページまたは最終ページの場合は逆順に並べ替え
if ($prev_id !== null || $page == $total_pages) {
    $members = array_reverse($members);
}
// 最後のIDを取得
$last_id = end($members)['member_id'] ?? null;
// 前ページのIDを取得
$prev_id = reset($members)['member_id'] ?? null;

// 最初のページクエリパラメータ作成
$first_page_url = "";
if ($page > 1) {
    $query_params = $_GET;
    // ページング情報を削除
    unset($query_params['last_id'], $query_params['prev_id']);
    // 最初のページ
    $query_params['page'] = 1;
    // 配列からクエリ文字列を生成する関数
    $first_page_url = "?" . http_build_query($query_params);
}

// 最後のページクエリパラメータ作成
$last_page_url = "";
$query_params = $_GET;
$query_params['page'] = $total_pages;
// 不要なパラメータを削除
unset($query_params['last_id']);
// 不要な前ページパラメータを削除
unset($query_params['prev_id']);
$last_page_url = "?" . http_build_query($query_params);
// ------------------------------------------------------------------------------------------------------------------------------

// 会員種別のリストを取得(検索プルダウン用)
$member_types_query = "SELECT type_id, type_name FROM member_types WHERE delete_flg = 0 ORDER BY type_id ASC";
$member_types_stmt = $pdo->prepare($member_types_query);
$member_types_stmt->execute();
// フェッチモード(配列のキー)はカラム名(会員種別名)のみ取得に指定
$member_types = $member_types_stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>会員一覧</title>
        <link rel="stylesheet" href="../assets/styles.css">
    </head>
    <body>
		<h1>会員一覧</h1>

		<a href="../index.html" class="button">トップへ戻る</a>
		<a href="../登録/user_register.php" class="button">会員登録画面</a>
		<br>

		<!-- 検索フォーム -->
		<form method="get" action="member_list.php">
			<div class="form-group">
    			<label for="id">会員ID:</label> <input type="text" pattern="\d*"
    				name="id" id="id"
    				value="<?php echo htmlspecialchars($id ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    
    			<label for="name">会員氏名:</label> 
    				<input maxlength='100' type="text" name="name" id="name" class="name-column"
    					value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    
    			<!-- 会員種別をプルダウンに反映 -->
    			<label for="member_type">会員種別:</label>
    			<select name="member_type" id="member_type">
    				<option value="">-- 選択してください --</option> 
                    	<?php foreach ($member_types as $type): ?>
                    		<option value="<?php echo htmlspecialchars($type['type_id'], ENT_QUOTES, 'UTF-8'); ?>"
        				<?php echo ($member_type == $type['type_id']) ? 'selected' : ''; ?>>
                    	<?php echo htmlspecialchars($type['type_name'], ENT_QUOTES, 'UTF-8'); ?>
                    	</option>
                    	<?php endforeach; ?>
        		</select>
        		
        		<div>
        		<label for="join_date_from">入会日(From):</label>
					<input type="date" name="join_date_from" id="join_date_from"
						value="<?php echo htmlspecialchars($join_date_from ?? '', ENT_QUOTES, 'UTF-8'); ?>">
				</div>
				
				<div>		
				<label for="join_date_to">～ 入会日(To):</label>
					<input type="date" name="join_date_to" id="join_date_to"
						value="<?php echo htmlspecialchars($join_date_to ?? '', ENT_QUOTES, 'UTF-8'); ?>">
				</div><br>

				<button type="submit" class="button">検索</button>
				<a href="member_list.php" type="reset" class="button">リセット</a> <br> <br>
			</div>
		</form>


        <!-- 検索結果表示 -->
    	<?php if ($members): ?>
        	<table border="1">
			<thead>
			<tr>
				<th>会員ID</th>
				<th>会員氏名</th>
				<th>入会日</th>
				<th>会員種別</th>
				<th>会員詳細</th>
			</tr>
			</thead>
				<tbody>
            		<?php foreach ($members as $member): ?>
                    	<tr>
            				<td><?php echo htmlspecialchars($member['member_id'], ENT_QUOTES, 'UTF-8'); ?></td>
            				<td><?php echo htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8'); ?></td>
            				<td><?php echo htmlspecialchars($member['join_date'], ENT_QUOTES, 'UTF-8'); ?></td>
            				<td><?= htmlspecialchars($member['type_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            				<td>
            					<a href="../会員詳細/detail.php?id=<?php echo htmlspecialchars($member['member_id'], ENT_QUOTES, 'UTF-8'); ?>"
            						class="button">詳細 </a>
            				</td>
            			</tr>
                	<?php endforeach; ?>
        		</tbody>
			</table>
    	<?php else: ?>
    		<p>条件に一致する会員が見つかりませんでした。</p>
    	<?php endif; ?><br>


	   <!-- ページングリンクの表示 -->
		<div class="pagination">

    		<!-- "最初へ" ボタン -->
        	<?php if ($page > 1) :
        	   $first_page_url = "?" . http_build_query(array_merge($_GET, [
        	       'page' => 1,
        	       'last_id' => null,
                   'prev_id' => null
        	       
        	   ]));
               echo "<a href='" . htmlspecialchars($first_page_url, ENT_QUOTES, 'UTF-8') . "'class='button'>最初へ</a>";
            ?>
        
        	<!-- "前へ" ボタン -->
            <?php
                $prev_page_url = "?" . http_build_query(array_merge($_GET, [
                    'page' => $page - 1,
                    'last_id' => null,
                    'prev_id' => $prev_id // 現在の最初のIDを渡す
                ]));
                echo "<a href='" . htmlspecialchars($prev_page_url, ENT_QUOTES, 'UTF-8') . "' class='button'>前へ</a>";
            ?>
        	<?php endif; ?>
        		
            <!-- ページ番号 -->
            <?php
                $page_range = 1; // 1度に表示するページ番号の数
                $start_page = max(1, $page - floor($page_range / 2)); // 開始ページ
                $end_page = min($total_pages, $start_page + $page_range - 1); // 終了ページ
            ?>
            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
            	
            	<!--現在のページはクリック不可-->
            	<?php if ($i == $page):?>
                	<span class="button active"><?php echo $i; ?></span>
            	<?php else: ?>
                	<a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"
                		class="button">
                    	<?php echo $i; ?>
                	</a>
            	<?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
            <!-- "次へ" ボタン -->
            <?php
                $next_page_url = "?" . http_build_query(array_merge($_GET, [
                    'page' => $page + 1,
                    'last_id' => $last_id, // 現在の最後のIDを渡す
                    'prev_id' => null
                    
                ]));
                    echo "<a href='" . htmlspecialchars($next_page_url, ENT_QUOTES, 'UTF-8') . "' class='button'>次へ</a>";
            ?>
            
            <!-- 最後へボタン -->
            <?php
                $last_page_url = "?" . http_build_query(array_merge($_GET, [
                    'page' => $total_pages,
                    'last_id' => null,
                    'prev_id' => null
                    
                ]));
                    echo "<a href='" . htmlspecialchars($last_page_url, ENT_QUOTES, 'UTF-8') . "'class='button'>最後へ</a>";
            ?>
            <?php endif; ?>
        </div>
    </body>
</html>