<?php
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

// 初期化
$errors        = array();
$success       = array();
$sql_kind      = '';
$user_id       = '';
$category_id   = '';
$category_name = '';

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

// リクエストメソッド取得
$request_method = getRequestMethod();

if (isset($_GET['success']) === TRUE && $_GET['success'] === 'insert') {
    $success[] = '新規商品追加しました';
}

if ($request_method === 'POST') {
    $sql_kind = getPostData('sql_kind');
    
    if ($sql_kind === 'category') {
        // POSTデータ
        $category_name = getPostData('category_name');
        
        // バリデーション
        if (checkEmpty($category_name, 'カテゴリー') !== TRUE) :
        elseif (checkLength($category_name, 20, 'カテゴリー') !== TRUE) :
        elseif (checkSpace($category_name, 'カテゴリー') !== TRUE) :
        endif;
        
        if (count($errors) === 0) {
            // insertのSQL
            if (insertCategoryTable($link, $category_name) !== TRUE) {
                $errors[] = 'item_table: insertエラー';
            }
            
            if (count($errors) === 0) {
                header('Location: admin_category.php?success=insert');
                exit();
            }
        }
        
    } else if ($sql_kind === 'category_update') {
        $category_id = getPostData('category_id');
        $update_category = getPostData('update_category');
        
        if (checkEmpty($category_id, 'カテゴリー') !== TRUE) :
        endif;
        if (checkEmpty($update_category, 'カテゴリー') !== TRUE) :
        elseif (checkLength($update_category, 20, 'カテゴリー') !== TRUE) :
        elseif (checkSpace($update_category, 'カテゴリー') !== TRUE) :
        endif;
        
        if (count($errors) === 0) {
            if (updateCategory($link, $update_category, $category_id) === TRUE) {
                $success[] = 'カテゴリー名をアップデートしました';
            } else {
                $errors[] = 'category_table: updateエラー';
            }
        }
    }
}
    
/**
* 商品情報を取得
*/
// データ取得
$category_data = getCategoryTableList($link);

closeDBConnect($link);

// 特殊文字を変換
$category_lists = entityAssocArray($category_data);

include_once '../../include/view/ec_view_practice/admin_category.php';