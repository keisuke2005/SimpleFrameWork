<?php
namespace FW\Exception;

require_once(__DIR__."/FwException.php");

/**
* ユーザ定義例外クラス
*
* ユーザ定義部分の例外を補足する、基底クラス
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class UserDefinedException extends FwException {
  /**
  * コンストラクタ
  *
  * エラーコード:2001 タイトル:ユーザ定義例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 2001;
    $this->message = 'ユーザ定義例外';
    $this->subMessage = $subMessage;
  }
}
