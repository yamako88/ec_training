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
        <form method="post" enctype="multipart/form-data" action="./admin_category.php">
            <div><label>カテゴリー名: <input type="text" name="category_name" value="<?php if (isset($category_name) === TRUE) { echo $category_name; } ?>"></label></div>
            <input type="hidden" name="sql_kind" value="category">
            <div><input type="submit" value="カテゴリーを登録する"></div>
        </form>
    </section>
    <section>
        <h2>カテゴリー情報の一覧・変更</h2>
<?php foreach ($success as $succes) {?>
        <h4><?php echo $succes; ?></h4>
<?php } ?>
        <table>
            <tr>
                <th>カテゴリー名</th>
            </tr>
<?php foreach ($category_lists as $category_list) { ?>
            <tr>
                <form method="post" action="./admin_category.php">
                    <td><input type="text" size="20" class="input_text_width text_align_right" name="update_category" value="<?php echo $category_list['name']; ?>">&nbsp;&nbsp;<input type="submit" value="変更"></td>
                    <input type="hidden" name="category_id" value="<?php echo $category_list['id']; ?>">
                    <input type="hidden" name="sql_kind" value="category_update">
                </form>
            <tr>
<?php } ?>
        </table>
    </section>
    </div>
</body>
</html>