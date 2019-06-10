<?php
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';
// セッション開始
session_start();
// セッション名取得 ※デフォルトはPHPSESSID
$session_name = session_name();

// ログアウト処理
logoutSession($session_name);

// ログアウトの処理が完了したらログインページへリダイレクト
header('Location: login.php');
exit;
