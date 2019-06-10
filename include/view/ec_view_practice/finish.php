<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>購入完了ページ</title>
    <link type="text/css" rel="stylesheet" href="./css/common.css">
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
        <ul>
<?php foreach ($errors as $error) {?>
            <li style="color: red;"><?php echo $error; ?></li>
<?php } ?>
        </ul>
<?php if (count($cart_lists) > 0 && count($errors) === 0) { ?>
        <div class="finish-msg">ご購入ありがとうございました。</div>
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
                    <span class="cart-item-price">¥<?php echo $cart_list['price']; ?></span>
                    <span class="finish-item-price"><?php echo $cart_list['amount']; ?></span>
                </div>
            </li>
    <?php } ?>
        </ul>
        <div class="buy-sum-box">
            <span class="buy-sum-title">合計</span>
            <span class="buy-sum-price">¥<?php echo $sum; ?></span>
        </div>
<?php } ?>
    </div>
</body>
</html>
