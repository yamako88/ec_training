<?php
ini_set('display_errors', "On");
require_once '../../include/conf/ec_conf_practice/const.php';
require_once '../../include/model/ec_model_practice/function.php';

// 初期化
$errors        = array();
$success       = array();
$name          = '';
$price         = '';
$stocks        = '';
$status        = '';
$sql_kind      = '';
$update_stock  = '';
$change_status = '';
$item_id       = '';
$user_id       = '';
$category_id   = '';

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
if (isset($_GET['success']) === TRUE && $_GET['success'] === 'delete') {
    $success[] = '商品削除に成功しました';
}

if ($request_method === 'POST') {
    $sql_kind = getPostData('sql_kind');

    if ($sql_kind === 'insert') {
        // POSTデータ
        $name     = getPostData('name');
        $price    = getPostData('price');
        $stocks   = getPostData('stocks');
        $status   = getPostData('status');
        $category_id = getPostData('category');

        // バリデーション
        if (checkEmpty($name, '名前') !== TRUE) :
        elseif (checkLength($name, 20, '名前') !== TRUE) :
        elseif (checkSpace($name, '名前') !== TRUE) :
        endif;
        if (checkEmpty($price, '値段') !== TRUE) :
        elseif (checkNumberIsValid($price, 5, '値段') !== TRUE) :
        endif;
        if (checkEmpty($stocks, '個数') !== TRUE) :
        elseif (checkNumberIsValid($stocks, 3, '個数') !== TRUE) :
        endif;
        if (checkEmpty($status, 'ステータス') !== TRUE) :
        elseif (checkBoolean($status, 'ステータス') !== TRUE) :
        endif;
        if (checkEmpty($category_id, 'カテゴリー') !== TRUE) :
        //TODO 存在しているカテゴリーIDかチェック
        elseif (checkCategoryID($link, $category_id) === TRUE) :
        endif;

        if (count($errors) === 0) {
            if (checkImageFile($_FILES, $_FILES['new_img'], '画像') !== TRUE) :
            elseif (checkImageSize($_FILES['new_img']['size']) !== TRUE) :
            elseif (checkImageEmpty($_FILES['new_img']['tmp_name'], $_FILES['new_img']['name']) !== TRUE) :
            else :
                $tmp_name = $_FILES['new_img']['tmp_name'];
                $img_name = $_FILES['new_img']['name'];

                $new_name = createRandFileName();
                $new_name = checkImageExt($tmp_name, $new_name);
                if (count($errors) === 0) {
                    $msg = uploadImageFile($new_name, $tmp_name);
                }
            endif;
        }

        if (count($errors) === 0) {
            // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
            dbAutocommitOff($link);
            // insertのSQL
            if (insertItemTable($link, $name, $price, $new_name, $status) === TRUE) {
                $insert_id = $link->lastInsertId();
                if (insertItemStockTable($link, $stocks, $insert_id) !== TRUE) {
                    $errors[] = 'item_stock_table: insertエラー';
                }
                if (insertItemCategory($link, $category_id, $insert_id) !== TRUE) {
                    $errors[] = 'item_category_table: insertエラー';
                }
            } else {
                $errors[] = 'item_table: insertエラー';
            }

            if (count($errors) === 0) {
                // 処理確定
                dbCommit($link);
                header('Location: admin_item.php?success=insert');
                exit();
            } else {
                // 処理取消
                dbRollback($link);
            }
        }

    } else if ($sql_kind === 'stock_update') {
        $item_id = getPostData('item_id');
        $update_stock = getPostData('update_stock');

        if (checkEmpty($item_id, '商品') !== TRUE) :
        endif;
        if (checkEmpty($update_stock, '個数') !== TRUE) :
        elseif (checkNumberIsValid($update_stock, 3, '個数') !== TRUE) :
        endif;

        if (count($errors) === 0) {
            if (updateStock($link, $update_stock, $item_id) === TRUE) {
                $success[] = '在庫数をアップデートしました';
            } else {
                $errors[] = 'item_stock_table: updateエラー';
            }
        }
    } else if ($sql_kind === 'status_update') {
        $item_id = getPostData('item_id');
        $change_status = getPostData('change_status');

        if (checkEmpty($item_id, '商品') !== TRUE) :
        endif;
        if (checkEmpty($change_status, 'ステータス') !== TRUE) :
        elseif (checkBoolean($change_status, 'ステータス') !== TRUE) :
        endif;

        if (count($errors) === 0) {
            if ($change_status === '1') {
                $change_status = 0;
            } else if ($change_status === '0') {
                $change_status = 1;
            }

            if (updateStatus($link, $change_status, $item_id) === TRUE) {
                $success[] = 'ステータスを更新しました';
            } else {
                $errors[] = 'item_status: updateエラー';
            }
        }
    } else if ($sql_kind === 'delete') {
        $item_id = getPostData('item_id');
        if (checkEmpty($item_id, '商品') !== TRUE) :
        endif;

        if (count($errors) === 0) {
            // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
            dbAutocommitOff($link);
            // insertのSQL
            if (deleteItemStocks($link, $item_id) === TRUE) {
                if (deleteItems($link, $item_id) !== TRUE) {
                    $errors[] = 'items_table: deleteエラー';
                }
            } else {
                $errors[] = 'item_stocks_table: deleteエラー';
            }

            if (count($errors) === 0) {
                // 処理確定
                dbCommit($link);
                header('Location: admin_item.php?success=delete');
                exit();
            } else {
                // 処理取消
                dbRollback($link);
            }
        }
    }
}

/**
* 商品情報を取得
*/
// データ取得
$item_data = getItemTableList($link);
$category_data = getCategoryTableList($link);
var_dump($item_data);

closeDBConnect($link);

// 特殊文字を変換
$item_lists = entityAssocArray($item_data);
$category_lists = entityAssocArray($category_data);

include_once '../../include/view/ec_view_practice/admin_item.php';
