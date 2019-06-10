<?php
function getDBConnect() {
    global $errors;

    try {
        $link = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';',
            DB_USER,
            DB_PASSWD,
            array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            )
        );
        return $link;
    } catch (PDOException $e) {
        $errors[] = 'DBの接続ができていません。。';
        exit('データベース接続失敗。' . $e->getMessage());
    } catch (Exception $e) {

    }
}


function closeDBConnect($link) {
    // 接続を閉じる
    // mysqli_close($link);
    $link = null;
}


function getRequestMethod() {
    return $_SERVER['REQUEST_METHOD'];
}


function getPostData($key) {
    $str = '';
    if (isset($_POST[$key]) === TRUE) {
        $str = $_POST[$key];
    }
    return $str;
}


function getGetData($key) {
    $str = '';
    if (isset($_GET[$key]) === TRUE) {
        $str = $_GET[$key];
    }
    return $str;
}


function getData($key) {
    $str = '';
    if (isset($_GET[$key]) === TRUE) {
        $str = $_GET[$key];
    }
    return $str;
}


function dbAutocommitOff($link) {
    // mysqli_autocommit($link, false);
    $link->beginTransaction();
}


function dbCommit($link) {
    // 処理確定
    // mysqli_commit($link);
    $link->commit();
}


function dbRollback($link) {
    // 処理取消
    // mysqli_rollback($link);
    $link->rollback();
}


function getCartSum($cart_lists) {
    $sum = array(0);
    foreach ($cart_lists as $cart_list) {
        $sum[] = $cart_list['price'] * $cart_list['amount'];
    }
    return array_sum($sum);
}


function changeSpaceFullToHalf($search_word) {
    $kana_keyword = mb_convert_kana($search_word, 'as', 'UTF-8');
    return convertIntoArray($kana_keyword);
}


function convertIntoArray($kana_keyword) {
    return preg_split("/[\s,]+/", "$kana_keyword");
}



// バリデーション　開始
function checkEmpty($value, $word) {
    global $errors;
    if ($value === '') {
        $errors[] = $word . 'を入力してください';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkLength($value, $length, $word) {
    global $errors;
    if (mb_strlen($value) > $length) {
        $errors[] = $word . 'は' . $length . '文字以内で入力してください';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkNumberIsValid($value, $length, $word) {
    global $errors;
    if (preg_match('/^[0-9]{1,' . $length . '}$/', $value) !== 1) {
        $errors[] = $word . 'は半角数字' . $length . '桁以内で入力してください';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkNumberZero($value, $word) {
    global $errors;
    if ($value === '0') {
        $errors[] = $word . 'に0は指定できません';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkBoolean($status, $word) {
    global $errors;
    if (preg_match('/^[01]$/', $status) !== 1) {
        $errors[] = '正しい' . $word . 'を選択してください';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkHalfwidthAlphanumeric($value, $word) {
    global $errors;
    if (preg_match('/\A[a-z\d]{6,16}+\z/i', $value) !==1) {
        $errors[] = $word . 'は6文字以上16文字以内で入力してください';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkSpace($value, $word) {
    global $errors;
    if (preg_match('/^[\s　]+$/', $value) === 1) {
        $errors[] = $word . 'は空白以外で入力してください';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkUnique($link, $user_name) {
    $prepare = $link->prepare(
        'select count(name) as "unique" from EC_users where name=?'
    );
    $prepare->bindValue(1, $user_name, PDO::PARAM_STR);

    return uniqueDB($prepare, 'このユーザー名は既に使われています');
}


function checkAlreadyCart($link, $user_id, $item_id) {
    $prepare = $link->prepare(
        'SELECT count(id) as "unique" FROM `EC_carts` WHERE user_id=? AND item_id=?'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);

    return alreadyDB($prepare, '');
}


function checkCategoryID($link, $category_id) {
    $prepare = $link->prepare(
        'SELECT count(id) as "unique" FROM EC_categories WHERE id=?'
    );
    $prepare->bindValue(1, (int)$category_id, PDO::PARAM_INT);

    return alreadyDB($prepare, '正しくカテゴリーを選択してください');
}


function uniqueDB($prepare, $word) {
    global $errors;
    if ($prepare->execute() === TRUE) {
        $result = $prepare->fetch(PDO::FETCH_ASSOC);
        $count = $result['unique'];
        if ($count > 0) {
            $errors[] = $word;
            return FALSE;
        } else {
            return TRUE;
        }
    } else {
        $errors[] = '[error] SQL失敗';
        return FALSE;
    }
}


function alreadyDB($prepare, $word) {
    global $errors;

    if ($prepare->execute() === TRUE) {
        $result = $prepare->fetch(PDO::FETCH_ASSOC);
        $count = $result['unique'];
        if ($count > 0) {
            return FALSE;
        } else {
            $errors[] = $word;
            return TRUE;
        }
    } else {
        $errors[] = '[error] SQL失敗';
        return FALSE;
    }
}


function checkArrayCount($keywords, $number, $word) {
    global $errors;
    if (count($keywords) > 3) {
        $errors[] = $word . 'は'. $number . '個までしか指定できません';
        return FALSE;
    } else {
        return TRUE;
    }
}
// バリデーション　終了



// DB Insert,update,delete 開始
function insertDB($prepare) {
    if ($prepare->execute() === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}


function updateStock($link, $update_stock, $item_id) {
    $prepare = $link->prepare(
        'UPDATE
            EC_item_stocks
        SET
            stock=?,
            update_datetime=now()
        WHERE
            item_id=?'
    );
    $prepare->bindValue(1, (int)$update_stock, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function updateCategory($link, $update_category, $category_id) {
    $prepare = $link->prepare(
        'UPDATE EC_categories SET name=?, update_datetime=now() WHERE id=?'
    );
    $prepare->bindValue(1, $update_category, PDO::PARAM_STR);
    $prepare->bindValue(2, (int)$category_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function updateStatus($link, $change_status, $item_id) {
    $prepare = $link->prepare(
        'UPDATE EC_items SET update_datetime=now(), status=? WHERE id=?'
    );
    $prepare->bindValue(1, (int)$change_status, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function insertItemTable($link, $name, $price, $new_name, $status) {
    $prepare = $link->prepare(
        'INSERT INTO EC_items(name, price, img, status, create_datetime, update_datetime)
            VALUES(?, ?, ?, ?, now(), now())'
    );
    $prepare->bindValue(1, $name, PDO::PARAM_STR);
    $prepare->bindValue(2, (int)$price, PDO::PARAM_INT);
    $prepare->bindValue(3, $new_name, PDO::PARAM_STR);
    $prepare->bindValue(4, (int)$status, PDO::PARAM_INT);

    return insertDB($prepare);
}


function insertCategoryTable($link, $category_name) {
    $prepare = $link->prepare(
        'INSERT INTO EC_categories(name, create_datetime, update_datetime)
            VALUES(?, now(), now())'
    );
    $prepare->bindValue(1, $category_name, PDO::PARAM_STR);

    return insertDB($prepare);
}


function insertItemStockTable($link, $stocks, $insert_id) {
    $prepare = $link->prepare(
        'INSERT INTO EC_item_stocks(item_id, stock, create_datetime, update_datetime)
            VALUES (?, ?, now(), now())'
    );
    $prepare->bindValue(1, (int)$insert_id, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$stocks, PDO::PARAM_INT);

    return insertDB($prepare);
}


function insertItemCategory($link, $category_id, $insert_id) {
    $prepare = $link->prepare(
        'INSERT INTO EC_item_category(item_id, category_id, create_datetime, update_datetime)
            VALUES (?, ?, now(), now())'
    );
    $prepare->bindValue(1, (int)$insert_id, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$category_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function deleteItemStocks($link, $item_id) {
    $prepare = $link->prepare(
        'DELETE FROM EC_item_stocks WHERE item_id =?'
    );
    $prepare->bindValue(1, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function deleteItems($link, $item_id) {
    $prepare = $link->prepare(
        'DELETE FROM EC_items WHERE id =?'
    );
    $prepare->bindValue(1, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function deleteCart($link, $cart_id) {
    $prepare = $link->prepare(
        'DELETE FROM EC_carts WHERE id=?'
    );
    $prepare->bindValue(1, (int)$cart_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function insertUser($link, $user_name, $password) {
    $prepare = $link->prepare(
        'INSERT INTO EC_users(name, password, create_datetime, update_datetime)
          VALUES (?, ?, now(), now())'
    );
    $prepare->bindValue(1, $user_name, PDO::PARAM_STR);
    $prepare->bindValue(2, $password, PDO::PARAM_STR);

    return insertDB($prepare);
}


function insertCart($link, $user_id, $item_id) {
    $prepare = $link->prepare(
        'INSERT INTO `EC_carts`(`user_id`, `item_id`, `amount`, `create_datetime`, `update_datetime`)
            VALUES (?, ?, 1, now(), now())'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function updateCartIncrement($link, $user_id, $item_id) {
    $prepare = $link->prepare(
        'UPDATE EC_carts SET amount=amount+1, update_datetime=now()
            WHERE user_id=? AND item_id=?'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function updateAmount($link, $update_amount, $cart_id) {
    $prepare = $link->prepare(
        'UPDATE EC_carts SET amount=?, update_datetime=now() WHERE id=?'
    );
    $prepare->bindValue(1, (int)$update_amount, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$cart_id, PDO::PARAM_INT);

    return insertDB($prepare);
}
// DB Insert,update 終了



// DB Select 開始
function getAsArray($prepare) {
    global $errors;

    if ($prepare->execute() === TRUE) {
        $result = $prepare->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errors[] = '[error] SQL失敗';
    }
    return $result;
}


function entityStr($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}


function entityAssocArray($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entityStr($values);
        }
    }
    return $assoc_array;
}


function getItemTableList($link) {
    $prepare = $link->prepare(
        'SELECT
            EC_items.id,
            EC_items.name,
            EC_items.price,
            EC_items.img,
            EC_items.status,
            EC_item_stocks.stock,
            EC_item_category.category_id,
            EC_categories.name AS category_name
        FROM
            EC_items
        JOIN
            EC_item_stocks ON EC_items.id = EC_item_stocks.item_id
        JOIN
            EC_item_category ON EC_items.id = EC_item_category.item_id
        JOIN
            EC_categories ON EC_item_category.category_id = EC_categories.id
        ORDER BY id DESC'
    );
    // クエリ実行
    return getAsArray($prepare);
}


function getCategoryTableList($link) {
    $prepare = $link->prepare(
        'SELECT id, name FROM EC_categories ORDER BY id DESC'
    );
    return getAsArray($prepare);
}


function getUserTableList($link) {
    $prepare = $link->prepare(
       'SELECT name, create_datetime
        FROM EC_users
        ORDER BY id DESC'
    );
    return getAsArray($prepare);
}


function getItemTableListPublic($link, $start) {
    $prepare = $link->prepare(
        'SELECT
        EC_items.id,
        EC_items.name,
        EC_items.price,
        EC_items.img,
        EC_items.status,
        EC_item_stocks.stock,
        EC_item_category.category_id,
        EC_categories.name AS category_name
        FROM EC_items
        JOIN EC_item_stocks ON EC_items.id = EC_item_stocks.item_id
        JOIN EC_item_category ON EC_items.id = EC_item_category.item_id
        JOIN EC_categories ON EC_item_category.category_id = EC_categories.id
        WHERE EC_items.status = 1
        ORDER BY id DESC
        LIMIT ?, 3'
    );
    $prepare->bindValue(1, (int)$start, PDO::PARAM_INT);

    return getAsArray($prepare);
}


function getItemTableCountPublic($link) {;
    $prepare = $link->prepare(
        'SELECT
            count(id) as total
        FROM EC_items
        WHERE status = 1'
    );

    return getAsArray($prepare);
}


function searchNameCount($link, $search_word) {
    $prepare = $link->prepare(
        'SELECT
            count(EC_items.id) as total
        FROM
            EC_items
        JOIN
            EC_item_stocks ON EC_items.id = EC_item_stocks.item_id
        JOIN
            EC_item_category ON EC_items.id = EC_item_category.item_id
        JOIN
            EC_categories ON EC_item_category.category_id = EC_categories.id
        WHERE
            EC_items.status = 1
        AND ?'
    );
    $prepare->bindValue(1, $search_word, PDO::PARAM_STR);

    return getAsArray($prepare);
}


function searchNameLittle($link, $search_word) {
    $prepare = $link->prepare(
        'SELECT
            EC_items.id,
            EC_items.name,
            EC_items.price,
            EC_items.img,
            EC_items.status,
            EC_item_stocks.stock,
            EC_item_category.category_id,
            EC_categories.name AS category_name
        FROM
            EC_items
        JOIN
            EC_item_stocks ON EC_items.id = EC_item_stocks.item_id
        JOIN
            EC_item_category ON EC_items.id = EC_item_category.item_id
        JOIN
            EC_categories ON EC_item_category.category_id = EC_categories.id
        WHERE
            EC_items.status = 1
        AND ?
        ORDER BY id DESC'
    );
    $prepare->bindValue(1, $search_word, PDO::PARAM_STR);

    return getAsArray($prepare);
}


function searchName($link, $search_word, $start) {
    $prepare = $link->prepare(
        'SELECT
            EC_items.id,
            EC_items.name,
            EC_items.price,
            EC_items.img,
            EC_items.status,
            EC_item_stocks.stock,
            EC_item_category.category_id,
            EC_categories.name AS category_name
        FROM
            EC_items
        JOIN
            EC_item_stocks ON EC_items.id = EC_item_stocks.item_id
        JOIN
            EC_item_category ON EC_items.id = EC_item_category.item_id
        JOIN
            EC_categories ON EC_item_category.category_id = EC_categories.id
        WHERE
            EC_items.status = 1
        AND ?
        ORDER BY id DESC
        LIMIT ?, 3'
    );
    $prepare->bindValue(1, $search_word, PDO::PARAM_STR);
    $prepare->bindValue(2, (int)$start, PDO::PARAM_INT);

    return getAsArray($prepare);
}


function getCartTableListPublic($link, $user_id) {
    $prepare = $link->prepare(
        'SELECT
            EC_items.id as "item_id",
            EC_items.name,
            EC_items.price,
            EC_items.img,
            EC_carts.id as "cart_id",
            EC_carts.amount
        FROM
            EC_items
        JOIN
            EC_carts ON EC_items.id = EC_carts.item_id
        WHERE EC_carts.user_id=?'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);

    return getAsArray($prepare);
}


function getCartItemListPublic($link, $user_id) {
    $prepare = $link->prepare(
        'SELECT
            EC_items.id as "item_id",
            EC_items.name,
            EC_items.price,
            EC_items.img,
            EC_items.status,
            EC_item_stocks.stock as "stock",
            EC_carts.id as "cart_id",
            EC_carts.amount
        FROM
            EC_items
        JOIN
            EC_carts ON EC_items.id = EC_carts.item_id
        JOIN
            EC_item_stocks ON EC_items.id = EC_item_stocks.item_id
        WHERE EC_carts.user_id=?'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);

    return getAsArray($prepare);
}

// 購入処理　開始
function checkAmountItem($amount, $stock, $name) {
    // global $errors;
    if ($amount > $stock) {
        // $errors[] = $name . 'は' . $stock . '個までしか購入できません';
        return $name . 'は' . $stock . '個までしか購入できません';
    }
}


function BuyItemStocks($link, $item_id, $amount) {
    $prepare = $link->prepare(
        'UPDATE EC_item_stocks SET stock=stock-?, update_datetime=now() WHERE item_id=?'
    );
    $prepare->bindValue(1, (int)$amount, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function deleteCarts($link, $cart_id) {
    $prepare = $link->prepare(
        'DELETE FROM `EC_carts` WHERE id=?'
    );
    $prepare->bindValue(1, (int)$cart_id, PDO::PARAM_INT);

    return insertDB($prepare);
}


function createSellHistory($link, $user_id, $item_id, $amount) {
    $prepare = $link->prepare(
        'INSERT INTO `EC_sell_history`(`user_id`, `item_id`, `amount`, `create_datetime`, `update_datetime`)
            VALUES (?, ?, ?, now(), now())'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);
    $prepare->bindValue(2, (int)$item_id, PDO::PARAM_INT);
    $prepare->bindValue(3, (int)$amount, PDO::PARAM_INT);

    return insertDB($prepare);
}
// DB Select 終了



// 画像処理　開始
function checkImageFile($files, $new_img, $word) {
    global $errors;
    if (isset($files) && isset($new_img) !== TRUE) {
        $errors[] = $word . 'が投稿できません';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkImageSize($img_size) {
    global $errors;
    if ($img_size >= IMG_SIZ) {
        $errors[] = '画像サイズを2MB以内にしてください。';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkImageEmpty($img_tmp, $img_name) {
    global $errors;
    if (is_uploaded_file($img_tmp) !== TRUE || $img_name === '') {
        $errors[] = '画像を選択してください。';
        return FALSE;
    } else {
        return TRUE;
    }
}


function checkImageExt($tmp_name, $new_name) {
    global $errors;
    switch (exif_imagetype($tmp_name)){
        case IMAGETYPE_JPEG:
            $new_name .= '.jpg';
            break;
        case IMAGETYPE_PNG:
            $new_name .= '.png';
            break;
        default:
            $errors[] = '画像はjpgがpngにしてください';
            return FALSE;
    }
    return $new_name;
}


function createRandFileName() {
    $new_name = date("YmdHis"); //ベースとなるファイル名は日付
    $new_name .= mt_rand(); //ランダムな数字も追加

    return $new_name;
}


function uploadImageFile($new_name, $tmp_name) {
    global $errors;
    $img_path = IMG_DIR . basename($new_name);
    // 自分のフォルダに移動
    if(move_uploaded_file($tmp_name, $img_path)){
        return $img_path. 'のアップロードに成功しました';
    }else {
        $errors[] = 'アップロードに失敗しました';
    }
}
// 画像処理　終了



// 認証　開始
function setCookieYear($cookie_name, $value) {
    setcookie($cookie_name, $value, time() + 60 * 60 * 24 * 365);
}


function getLoginUser($link, $user_name, $password) {
    $prepare = $link->prepare(
       'SELECT id, name, password
        FROM EC_users
        WHERE name = ?
        AND password = ?'
    );
    $prepare->bindValue(1, $user_name, PDO::PARAM_STR);
    $prepare->bindValue(2, $password, PDO::PARAM_STR);

    return getAsArray($prepare);
}


function logoutSession($session_name) {
    // セッション変数を全て削除
    $_SESSION = array();

    // ユーザのCookieに保存されているセッションIDを削除
    if (isset($_COOKIE[$session_name])) {
      // sessionに関連する設定を取得
      $params = session_get_cookie_params();

      // sessionに利用しているクッキーの有効期限を過去に設定することで無効化
      setcookie($session_name, '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }

    // セッションIDを無効化
    session_destroy();
}


function checkLogin() {
    // セッション変数からuser_id取得
    if (isset($_SESSION['user_id']) === TRUE) {
       return $_SESSION['user_id'];
    } else {
       // 非ログインの場合、ログインページへリダイレクト
       header('Location: login.php');
       exit;
    }
}


function checkAdminLogin() {
     // セッション変数からuser_id取得
    if (isset($_SESSION['user_id']) === TRUE && $_SESSION['is_admin'] === TRUE) {
       return $_SESSION['user_id'];
    } else {
       // 非ログインの場合、ログインページへリダイレクト
       header('Location: login.php');
       exit;
    }
}


function getLoginUserName($link, $user_id) {
    $prepare = $link->prepare(
        'SELECT name FROM EC_users WHERE id = ?'
    );
    $prepare->bindValue(1, (int)$user_id, PDO::PARAM_INT);

    return getAsArray($prepare);
}


function checkUserName($name) {
    // ユーザ名を取得できたか確認
    if (isset($name)) {
       return $name;
    } else {
       // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
       header('Location: logout.php');
       exit;
    }
}

// 認証　終了
