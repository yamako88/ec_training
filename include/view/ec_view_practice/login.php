<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログインページ</title>
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
                    <button type="button" class="btn btn-primary">Log In</button>
                </a>
                <a href="./register.php">
                    <button type="button" class="btn btn-success register-btn">Sign Up</button>
                </a>
            </nav>
        </div>
    </header>
    <div class="content">
        <div class="login">
            <div class="main">
                <p class="sign" align="center">Log In</p>
                <form method="post" action="./login.php">
                  <input class="un " type="text" align="center" placeholder="Username" name="user_name">
                  <input class="pass" type="password" align="center" placeholder="Password" name="password">
                  <input type="submit" value="Log In" class="submit">
                  <p class="forgot" align="center"><a href="./register.php">New User Registration</a>?</p>
                </form>
                <ul class="center-msg">
<?php foreach ($errors as $error) {?>
                    <li style="color: red;"><?php echo $error; ?></li>
<?php } ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>