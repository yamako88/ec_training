<?php
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

$user_id = '';

// セッション開始
session_start();
// ログインチェック
$user_id = checkAdminLogin();
$link = getDBConnect();
$data = getLoginUserName($link, $user_id);
closeDBConnect($link);
$user_name = checkUserName($data[0]['name']);

// DB接続
$link = getDBConnect();

/**
* ユーザー情報を取得
*/
// データ取得
$user_data = getUserTableList($link);

closeDBConnect($link);

// 特殊文字を変換
$user_lists = entityAssocArray($user_data);

include_once '../../include/view/ec_view_practice/admin_user.php';