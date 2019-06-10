<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>商品一覧ページ</title>
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
                            <!--<a class="nemu" href="./admin_item.php">購入履歴</a>-->
                            <a class="nemu" href="./logout.php">ログアウト</a>
                        </div>
                    </div>
                    <form class="form-inline my-2 my-lg-0" action="./top.php" method="get">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search_word" value="<?php if ($search_keyword !== '') { echo $search_keyword; }?>">
                        <input type="hidden" name="sql_kind" value="search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    
    <div class="content">
        <ul>
<?php foreach ($errors as $error) {?>
            <li style="color: red;"><?php echo $error; ?></li>
<?php } ?>
        </ul>
<?php foreach ($success as $succes) {?>
        <h4><?php echo $succes; ?></h4>
<?php } ?>
<?php if ($search_keyword !== '') { ?>
        <h4>検索キーワード：<?php echo $search_keyword; ?></h4>
<?php } ?>
<?php if ($item_lists === array() && count($errors) === 0) { ?>
        <h4>該当する商品はありませんでした</h4>
<?php } ?>

    <div class="row">
        <!--<h2>トレンド</h2>-->
        <ul class="item-list">
        <!--    <div class="sample-item">-->
        <!--    <li>-->
        <!--        <div class="card box-shadow" style="width: 18rem;">-->
        <!--            <form action="./top.php>" method="post">-->
        <!--          <img src="./images/20190530131635496350449.png" class="card-img-top card-image" alt="...">-->
        <!--          <div class="card-body">-->
        <!--            <h5 class="card-title">あさｄふぁｓｄｆ</h5>-->
        <!--            <p class="card-text">カテゴリー: トップス></p>-->
        <!--            <p class="card-text">￥1000</p>-->
        <!--                <input class="btn-flat-border" type="submit" value="カートに入れる">-->
        <!--                <input type="hidden" name="item_id" value=">">-->
        <!--                <input type="hidden" name="sql_kind" value="insert_cart">-->
        <!--            </form>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--    </li>-->
        <!--    <li>-->
        <!--        <div class="card box-shadow" style="width: 18rem;">-->
        <!--            <form action="./top.php>" method="post">-->
        <!--          <img src="./images/20190530131635496350449.png" class="card-img-top card-image" alt="...">-->
        <!--          <div class="card-body">-->
        <!--            <h5 class="card-title">あさｄふぁｓｄｆ</h5>-->
        <!--            <p class="card-text">カテゴリー: トップス></p>-->
        <!--            <p class="card-text">￥1000</p>-->
        <!--                <input class="btn-flat-border" type="submit" value="カートに入れる">-->
        <!--                <input type="hidden" name="item_id" value=">">-->
        <!--                <input type="hidden" name="sql_kind" value="insert_cart">-->
        <!--            </form>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--    </li>-->
        <!--    <li>-->
        <!--        <div class="card box-shadow" style="width: 18rem;">-->
        <!--            <form action="./top.php>" method="post">-->
        <!--          <img src="./images/20190530131635496350449.png" class="card-img-top card-image" alt="...">-->
        <!--          <div class="card-body">-->
        <!--            <h5 class="card-title">あさｄふぁｓｄｆ</h5>-->
        <!--            <p class="card-text">カテゴリー: トップス></p>-->
        <!--            <p class="card-text">￥1000</p>-->
        <!--                <input class="btn-flat-border" type="submit" value="カートに入れる">-->
        <!--                <input type="hidden" name="item_id" value=">">-->
        <!--                <input type="hidden" name="sql_kind" value="insert_cart">-->
        <!--            </form>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--    </li>-->
        <!--    </div>-->
        <!--    <div class="search-sort">-->
        <!--    <select name="category">-->
        <!--        <option value="">カテゴリーを選択してください</option>-->
        <!--        <option value="">80s</option>-->
        <!--        <option value="">70s</option>-->
        <!--    </select>-->
        <!--    <select name="category">-->
        <!--        <option value="">年代を選択してください</option>-->
        <!--        <option value="">80s</option>-->
        <!--        <option value="">70s</option>-->
        <!--    </select>-->
        <!--    <select name="category">-->
        <!--        <option value="">ソートを選択してください</option>-->
        <!--        <option value="">80s</option>-->
        <!--        <option value="">70s</option>-->
        <!--    </select>-->
        <!--    <button>検索</button>-->
        <!--    </div>-->
<?php foreach ($item_lists as $item_list) { ?>
            <li>
    <!--            <div class="item">-->
    <!--                <form action="./top.php" method="post">-->
    <!--                    <img class="item-img" src="<?php echo IMG_DIR . $item_list['img']; ?>" >-->
    <!--                    <div class="item-info">-->
    <!--                        <span class="item-name"><?php echo $item_list['name']; ?></span><br>-->
    <!--                        <span class="item-name">カテゴリー: <?php echo $item_list['category_name']; ?></span>-->
    <!--                        <span class="item-price">¥<?php echo $item_list['price']; ?></span>-->
    <!--                    </div>-->
    <!--<?php if ($item_list['stock'] === '0') { ?>-->
    <!--                    <span class="red" style="color: red;">売り切れ</span>-->
    <!--<?php } else { ?>-->
    <!--                    <input class="cart-btn" type="submit" value="カートに入れる">-->
    <!--                    <input type="hidden" name="item_id" value="<?php echo $item_list['id']; ?>">-->
    <!--                    <input type="hidden" name="sql_kind" value="insert_cart">-->
    <!--<?php } ?>-->
    <!--                </form>-->
    <!--            </div>-->
                <div class="card box-shadow" style="width: 18rem;">
                    <form action="./top.php?page=<?php echo $page; ?>" method="post">
                  <img src="<?php echo IMG_DIR . $item_list['img']; ?>" class="card-img-top card-image" alt="...">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $item_list['name']; ?></h5>
                    <p class="card-text">カテゴリー: <?php echo $item_list['category_name']; ?></p>
                    <p class="card-text">￥<?php echo $item_list['price']; ?></p>
                        <?php if ($item_list['stock'] === '0') { ?>
                        <span class="red" style="color: red;">売り切れ</span>
    <?php } else { ?>
                        <input class="btn-flat-border" type="submit" value="カートに入れる">
                        <input type="hidden" name="item_id" value="<?php echo $item_list['id']; ?>">
                        <input type="hidden" name="sql_kind" value="insert_cart">
    <?php } ?>
                    </form>
                  </div>
                </div>
            </li>
<?php } ?>
        </ul>
    </div>
    <div class="row">
        <nav class="cp_navi">
            <div class="cp_pagination">
                
                <a class="cp_pagenum prev <?php if ($page < 2) { echo 'hidden'; } ?>" href="<?php echo $page_back_url; ?>">prev</a>
                <?php if ($count > 3) { ?>
                    <span aria-current="page" class="cp_pagenum current"><?php echo $page ?></span>
                <?php } ?>
                <a class="cp_pagenum next <?php if ($pagination <= $page) { echo 'hidden'; } ?>" href="<?php echo $page_forward_url; ?>">next</a>
            </div>
        </nav>
    </div>
    </div>
</body>
</html>
