<?php
ini_set('display_errors', "On");
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

$user_id    = '';
$errors     = array();
$cart_lists = array();
$cart_data  = array();

// セッション開始
session_start();
// ログインチェック
$user_id = checkLogin();
$link = getDBConnect();
$data = getLoginUserName($link, $user_id);
closeDBConnect($link);
$user_name = checkUserName($data[0]['name']);

// リクエストメソッド取得
$request_method = getRequestMethod();

if ($request_method === 'POST') {
    // DB接続
    $link = getDBConnect();

    // データ取得
    $cart_data = getCartItemListPublic($link, $user_id);

    if ($cart_data === array()) {
        $errors[] = 'カートに商品がありません';
    }

    $cart_lists = entityAssocArray($cart_data);

    foreach ($cart_lists as $cart_list) {
        $msg = checkAmountItem($cart_list['amount'], $cart_list['stock'], $cart_list['name']);
        if (isset($msg) === TRUE) {
            $errors[] = $msg;
        }
        if ($cart_list['status'] !== '1') {
            $errors[] = $cart_list['name'] . 'は現在販売していません';
        }
    }

    if (count($errors) === 0) {
        foreach ($cart_data as $cart_list) {
            dbAutocommitOff($link);
            // insertのSQL
            if (BuyItemStocks($link, $cart_list['item_id'], $cart_list['amount']) === TRUE) {
                if (deleteCarts($link, $cart_list['cart_id']) !== TRUE) {
                    $errors[] = 'carts_table: deleteエラー';
                }
                if (createSellHistory($link, $user_id, $cart_list['item_id'],$cart_list['amount']) !== TRUE) {
                    $errors[] = 'cart_history_table: insert: エラー';
                }
            } else {
                $errors[] = 'item_stocks_table: update stockエラー';
            }

            if (count($errors) === 0) {
                // 処理確定
                dbCommit($link);
            } else {
                // 処理取消
                dbRollback($link);
            }
        }
    }

    closeDBConnect($link);

} else {
    $errors[] = '[error] 不正な処理です';
}

if (count($errors) === 0) {
    // 特殊文字を変換
    $cart_lists = entityAssocArray($cart_data);
    $sum = getCartSum($cart_lists);
}

include_once '../../include/view/ec_view_practice/finish.php';
