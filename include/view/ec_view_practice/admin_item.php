<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>商品管理ページ</title>
    <link type="text/css" rel="stylesheet" href="./css/admin.css">
    <script type="text/javascript" src="./js/main.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <div class="header-box">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <h1 class="logo-header">CodeSHOP 管理ページ</h1>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <a class="nemu" href="./admin_item.php">商品管理ページ</a>
                            <a class="nemu" href="./admin_user.php">ユーザー管理ページ</a>
                            <a class="nemu" href="./admin_category.php">カテゴリー管理ページ</a>
                            <a class="nemu" href="./top.php">商品購入トップページ</a>
                            <p class="nemu">ユーザー名：<?php echo $user_name; ?></p>
                            <a class="nemu" href="./logout.php">ログアウト</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <div class="center-msg">
    <section>
        <h2>商品の登録</h2>
        <ul>
<?php foreach ($errors as $error) {?>
            <li style="color: red;"><?php echo $error; ?></li>
<?php } ?>
        </ul>
        <form method="post" enctype="multipart/form-data" action="./admin_item.php">
            <div><label>商品名: <input type="text" name="name" value="<?php if (isset($name) === TRUE) { echo $name; } ?>"></label></div>
            <div><label>値　段: <input type="text" name="price" value="<?php if (isset($price) === TRUE) { echo $price; } ?>"></label></div>
            <div><label>個　数: <input type="text" name="stocks" value="<?php if (isset($stocks) === TRUE) { echo $stocks; } ?>"></label></div>
            <div><label>商品画像:<input type="file" name="new_img"></label></div>
            <div><label>カテゴリー:
                    <select name="category">
                        <option value="">カテゴリーを選択してください</option>
<?php foreach ($category_lists as $category_list) { ?>
                        <option value="<?php echo $category_list['id']?>"><?php echo $category_list['name']?></option>
<?php } ?>
                    </select>
                </label>
            </div>
            <!--<div><label>年代:-->
            <!--        <select name="category">-->
            <!--            <option value="">年代を選択してください</option>-->
            <!--            <option value="">80s</option>-->
            <!--            <option value="">70s</option>-->
            <!--        </select>-->
            <!--    </label>-->
            <!--</div>-->
            <div><label>ステータス:
                    <select name="status">
                        <option value="0">非公開</option>
                        <option value="1">公開</option>
                    </select>
                </label>
            </div>
            <input type="hidden" name="sql_kind" value="insert">
            <div><input type="submit" value="商品を登録する"></div>
        </form>
    </section>
    <section>
        <h2>商品情報の一覧・変更</h2>
<?php foreach ($success as $succes) {?>
        <h4><?php echo $succes; ?></h4>
<?php } ?>
        <table>
            <tr>
                <th>商品画像</th>
                <th>カテゴリー</th>
                <!--<th>年代</th>-->
                <th>商品名</th>
                <th>価　格</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
<?php foreach ($item_lists as $item_list) { ?>
            <tr class="<?php if ($item_list['status'] === '0') { echo 'status_false'; } ?>">
                <form method="post" action="./admin_item.php">
                    <td><img width="100px" src="<?php echo IMG_DIR . $item_list['img']; ?>"></td>
                    <td class="name_width"><?php echo $item_list['category_name']; ?></td>
                    <!--<td class="name_width">80s</td>-->
                    <td class="name_width"><?php echo $item_list['name']; ?></td>
                    <td class="text_align_right"><?php echo $item_list['price']; ?>円</td>
                    <td><input type="text"  class="input_text_width text_align_right" name="update_stock" value="<?php echo $item_list['stock']; ?>">個&nbsp;&nbsp;<input type="submit" value="変更"></td>
                    <input type="hidden" name="item_id" value="<?php echo $item_list['id']; ?>">
                    <input type="hidden" name="sql_kind" value="stock_update">
                </form>
                <form method="post" action="./admin_item.php">
                    <td><input type="submit" value="<?php if ($item_list['status'] === '1') { echo '公開 → 非公開にする'; } else { echo '非公開 → 公開にする'; } ?>"></td>
                    <input type="hidden" name="change_status" value="<?php echo $item_list['status']; ?>">
                    <input type="hidden" name="item_id" value="<?php echo $item_list['id']; ?>">
                    <input type="hidden" name="sql_kind" value="status_update">
                </form>
                <form method="post" onsubmit="return submitChk()" action="./admin_item.php">
                    <td><input type="submit" value="削除する"></td>
                    <input type="hidden" name="item_id" value="<?php echo $item_list['id']; ?>">
                    <input type="hidden" name="sql_kind" value="delete">
                </form>
            <tr>
<?php } ?>
        </table>
    </section>
    </div>
</body>
</html>