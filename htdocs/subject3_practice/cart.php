<?php
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

$user_id   = '';
$errors    = array();
$success   = array();
$sum       = 0;
$cart_data = array();

// セッション開始
session_start();
// ログインチェック
$user_id = checkLogin();
$link = getDBConnect();
$data = getLoginUserName($link, $user_id);
closeDBConnect($link);
$user_name = checkUserName($data[0]['name']);

// DB接続
$link = getDBConnect();

// リクエストメソッド取得
$request_method = getRequestMethod();

if ($request_method === 'POST') {
    $sql_kind = getPostData('sql_kind');
    
    if ($sql_kind === 'change_cart') {
        $cart_id = getPostData('cart_id');
        $update_amount = getPostData('update_amount');
        
        if (checkEmpty($cart_id, '商品') !== TRUE) :
        endif;
        if (checkEmpty($update_amount, '個数') !== TRUE) :
        elseif (checkNumberIsValid($update_amount, 3, '個数') !== TRUE) :
        elseif (checkNumberZero($update_amount, '個数') !== TRUE) :
        endif;
        
        if (count($errors) === 0) {
            if (updateAmount($link, $update_amount, $cart_id) === TRUE) {
                $success[] = '数量をアップデートしました';
            } else {
                $errors[] = 'item_amount_table: updateエラー';
            }
        }
    } else if ($sql_kind === 'delete_cart') {
        $cart_id = getPostData('cart_id');
        if (checkEmpty($cart_id, '商品') !== TRUE) :
        endif;
        
        if (count($errors) === 0) {
            // insertのSQL
            if (deleteCart($link, $cart_id) === TRUE) {
                $success[] = '削除しました';
            } else {
                $errors[] = 'item_stocks_table: deleteエラー';
            }
            
            header('Location: cart.php?success=delete');
            exit();
        }
    }
}

// データ取得
$cart_data = getCartTableListPublic($link, $user_id);

closeDBConnect($link);

// 特殊文字を変換
$cart_lists = entityAssocArray($cart_data);
$sum = getCartSum($cart_lists);

include_once '../../include/view/ec_view_practice/cart.php';