<?php
namespace FW\Library;

/**
* データベースアクセスオブジェクト
*
* データベースオブジェクト管理及びSQL発行
*
* 本フレームワーク方針として、生のSQLを書き、不必要にラップしない
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
class Judgement
{
  /**
  * 配列内部ユニーク型チェック
  *
  * 配列の内部に保管されている型が第二引数で統一されているかを判定する
  *
  * タイプヒンティングで中身まで確認できないので、配列型を関数の引数にする場合は、こちらを使ってほしい
  * @access public
  * @param array $types
  * @param string $classname
  * @return bool
  */
  public static function isUnifiedArrayType(array $types,string $classname): bool
  {
    return ! in_array(
      false,
      array_map(
        fn($T) => gettype($T) === "object" ? get_class($T) === $classname : gettype($T) === $classname,
        $types
      )
    );
  }

  /**
  * 配列内部混在型チェック
  *
  * 配列の内部に保管されている型が第二引数に指定しているクラスではないものがあればtrueを返す
  * @access public
  * @param array $types
  * @param string $classname
  * @return bool
  */
  public static function isMixedArrayType(array $types,string $classname): bool
  {
    return in_array(
      false,
      array_map(
        fn($T) => gettype($T) === "object" ? get_class($T) === $classname : gettype($T) === $classname,
        $types
      )
    );
  }

}
