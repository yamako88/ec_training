<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ショッピングカートページ</title>
    <link type="text/css" rel="stylesheet" href="./css/common.css">
    <script type="text/javascript" src="./js/main.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="./css/style.css">
</head>
<body class="body-background">
    <header>
        <div class="header-box">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a href="./top.php">
                    <img class="logo" src="./images/logo.png" alt="CodeSHOP">
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <p class="nemu">ようこそ、<?php echo $user_name; ?> さん</p>
        <?php if (isset($_SESSION['is_admin']) === TRUE && $_SESSION['is_admin'] === TRUE) { ?>
                            <a class="nemu" href="./admin_item.php">管理画面</a>
        <?php } ?>
                            <a href="./cart.php" class="cart"></a>
                            <a class="nemu" href="./logout.php">ログアウト</a>
                        </div>
                </div>
              </div>
            </nav>
        </div>
    </header>
    <div class="content cart-content">
        <h1 class="title">ショッピングカート</h1>
        <ul>
<?php foreach ($errors as $error) {?>
            <li style="color: red;"><?php echo $error; ?></li>
<?php } ?>
        </ul>
<?php foreach ($success as $succes) {?>
        <h4><?php echo $succes; ?></h4>
<?php } ?>

        <div class="cart-list-title">
            <span class="cart-list-price">価格</span>
            <span class="cart-list-num">数量</span>
        </div>
        <ul class="cart-list">
<?php foreach($cart_lists as $cart_list) { ?>
            <li>
                <div class="cart-item">
                    <img class="cart-item-img" src="<?php echo IMG_DIR . $cart_list['img']; ?>">
                    <span class="cart-item-name"><?php echo $cart_list['name']; ?></span>
                    <form class="cart-item-del" action="./cart.php" method="post" onsubmit="return submitChk()">
                        <button type="submit" value="削除" class="btn btn-danger btn-xs">削除</button>
                        <input type="hidden" name="cart_id" value="<?php echo $cart_list['cart_id']; ?>">
                        <input type="hidden" name="sql_kind" value="delete_cart">
                    </form>
                    <span class="cart-item-price">¥ <?php echo $cart_list['price']; ?></span>
                    <form class="form_select_amount" id="form_select_amount139" action="./cart.php" method="post">
                        <input type="text" class="cart-item-num2" min="0" name="update_amount" value="<?php echo $cart_list['amount']; ?>">個&nbsp;<input type="submit" value="変更する" class="btn btn-success">
                        <input type="hidden" name="cart_id" value="<?php echo $cart_list['cart_id']; ?>">
                        <input type="hidden" name="sql_kind" value="change_cart">
                    </form>
                </div>
            </li>
<?php } ?>
        </ul>
        <div class="buy-sum-box">
            <span class="buy-sum-title">合計</span>
            <span class="buy-sum-price">¥<?php echo $sum; ?></span>
        </div>
        <div>
            <form action="./finish.php" method="post">
                <input class="buy-btn" type="submit" value="購入する">
            </form>
        </div>
    </div>
</body>
</html>