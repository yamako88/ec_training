function submitChk () {
    /* 確認ダイアログ表示 */
    var flag = confirm ( "本当に削除してもよろしいですか？");
    /* send_flg が TRUEなら送信、FALSEなら送信しない */
    return flag;
}