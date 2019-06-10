<?php
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

$errors = array();

if (getRequestMethod() === 'POST') {
    // セッション開始
    session_start();
    // POST値取得
    $user_name  = getPostData('user_name');  // ユーザー名
    $password = getPostData('password'); // パスワード
    setCookieYear('user_name', $user_name);

    $link = getDBConnect();

    $data = getLoginUser($link, $user_name, $password);

    closeDBConnect($link);

    // 登録データを取得できたか確認
    if (isset($data[0]['id'])) {
        // セッション変数にuser_idを保存
        $_SESSION['user_id'] = $data[0]['id'];
        if($data[0]['name'] === 'admin' && $data[0]['password'] === 'admin') {
            $_SESSION['is_admin'] = TRUE;
            header('Location: admin_item.php');
            exit;
        }
        // ログイン済みユーザのホームページへリダイレクト
        header('Location: top.php');
        exit;
    } else {
        // セッション変数にログインのエラーフラグを保存
        $_SESSION['login_err_flag'] = TRUE;
        // ログインページへリダイレクト
        header('Location: login.php?errors=login');
        exit;
    }
} else {
    // セッション開始
    session_start();

    // セッション変数からログイン済みか確認
    if (isset($_SESSION['user_id']) === TRUE) {
       // ログイン済みの場合、ホームページへリダイレクト
       header('Location: top.php');
       exit;
    }
    $login_error = getData('errors');

    if ($login_error === 'login') {
        $errors[] = 'ログインに失敗しました。正しい名前とパスワードを入力してください。';
    }
}

include_once '../../include/view/ec_view_practice/login.php';
