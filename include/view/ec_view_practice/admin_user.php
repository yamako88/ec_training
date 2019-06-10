<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ユーザ管理ページ</title>
    <link type="text/css" rel="stylesheet" href="./css/admin.css">
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
    <h2>ユーザ情報一覧</h2>
    <table>
        <tr>
            <th>ユーザID</th>
            <th>登録日</th>
        </tr>
<?php foreach($user_lists as $user_list) { ?>
        <tr>
            <td class="name_width"><?php echo $user_list['name']; ?></td>
            <td><?php echo $user_list['create_datetime']; ?></td>
        </tr>
<?php } ?>
    </table>
    </section>
    </div>
</body>
</html>