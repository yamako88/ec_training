<?php
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

$user_id          = '';
$errors           = array();
$success          = array();
$search_word      = '';
$item_data        = array();
$keywordCondition = array();
$page             = 1;
$pagination       = 1;
$count            = 0;
$item_count       = array();

// セッション開始
session_start();
// ログインチェック
$user_id = checkLogin();
$link = getDBConnect();
$data = getLoginUserName($link, $user_id);
// 参照渡し（&を変数の最初に書く）、値渡し
closeDBConnect($link);
$user_name = checkUserName($data[0]['name']);

// DB接続
$link = getDBConnect();

// リクエストメソッド取得
$request_method = getRequestMethod();

// GETで現在のページ数を取得する（未入力の場合は1を挿入）
    if (isset($_GET['page'])) {
        $page = (int)$_GET['page'];
    } else {
        $page = 1;
    }
    if ($page > 1) {
        $start_limit = ($page * 3) - 3;
    } else {
        $start_limit = 0;
    }
    $back = $page-1;
    $start = $page+1;


if ($request_method === 'POST') {
    $sql_kind = getPostData('sql_kind');

    if ($sql_kind === 'insert_cart') {
        $item_id = getPostData('item_id');

        if (checkEmpty($item_id, '商品') !== TRUE) :
        endif;

        if (count($errors) === 0) {
            if (checkAlreadyCart($link, $user_id, $item_id) === TRUE) {
                if (insertCart($link, $user_id, $item_id) === TRUE) {
                    $success[] = 'カートに追加しました';
                } else {
                    $errors[] = 'cart_table: insertエラー';
                }
            } else {
                if (updateCartIncrement($link, $user_id, $item_id) === TRUE) {
                    $success[] = 'カートに追加しました';
                } else {
                    $errors[] = 'cart_table: updateエラー';
                }
            }
        }
        // データ取得
        $item_data = getItemTableListPublic($link, $start);

        $item_count = getItemTableCountPublic($link);

        // 特殊文字を変換
        $item_lists = entityAssocArray($item_data);

    }

} else if ($request_method === 'GET') {
    $sql_kind = getGetData('sql_kind');

    if ($sql_kind === 'search') {
        $search_word = getGetData('search_word');

        // スペースを半角に変換、文字列を配列に変換
        $keywords = changeSpaceFullToHalf($search_word);

        if (checkEmpty($search_word, '検索ワード') !== TRUE) :
            header('Location: top.php');
            exit;
        elseif (checkArrayCount($keywords, 3, '検索ワード') !== TRUE) :
        endif;

        if (count($errors) === 0) {
            foreach ($keywords as $keyword) {
                $keywordCondition[] = '(EC_items.name LIKE "%' . $keyword .'%"
                                        OR EC_categories.name LIKE "%' . $keyword .'%")';
            }
            $keywordCondition = implode(' AND ', $keywordCondition);
            $item_count = searchNameCount($link, $keywordCondition);
            if ($item_count[0]['total'] < 3) {
                $item_data = searchNameLittle($link, $keywordCondition, $start);
            } else {
                $item_data = searchName($link, $keywordCondition, $start);
            }

            $page_back_url = 'top.php?page=' . $back . '&sql_kind=' . $sql_kind . '&search_word=' . $search_word;
            $page_forward_url = 'top.php?page=' . $start . '&sql_kind=' . $sql_kind . '&search_word=' . $search_word;
        }

    } else {
    // データ取得
        $item_data = getItemTableListPublic($link, $start_limit);

        $item_count = getItemTableCountPublic($link);
        $page_back_url = '?page=' . $back;
        $page_forward_url = '?page=' . $start;
    }
}

$count = $item_count[0]['total'];
$pagination = ceil($count / 3);

closeDBConnect($link);

// 特殊文字を変換
$item_lists = entityAssocArray($item_data);
$search_keyword = entityStr($search_word);

include_once '../../include/view/ec_view_practice/top.php';
