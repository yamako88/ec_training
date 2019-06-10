<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ユーザ登録ページ</title>
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
                <a href="./login.php" class=" text-right">
                    <button type="button" class="btn btn-primary text-right">Log In</button>
                </a>
                <a href="./register.php">
                    <button type="button" class="btn btn-success register-btn">Sign Up</button>
                </a>
            </nav>
        </div>
    </header>
    <div class="content">
        <div class="register">
            <div class="main">
                <p class="sign" align="center">Sign Up</p>
                <form method="post" action="./register.php">
                  <input class="un " type="text" align="center" placeholder="New Username" name="user_name">
                  <input class="pass" type="password" align="center" placeholder="New Password" name="password">
                  <input type="submit" value="Sign Up" class="submit">
                  <p class="forgot" align="center"><a href="./login.php">Go to Log In page</a>?</p>
                 </form>
                <ul class="center-msg">
<?php foreach ($errors as $error) {?>
                    <li style="color: red;"><?php echo $error; ?></li>
<?php } ?>
                </ul>
            <?php foreach ($success as $succes) {?>
                <h5 class="center-msg"><?php echo $succes; ?></h5>
<?php } ?>
            </div>

        </div>
    </div>
</body>
</html>