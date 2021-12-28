<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

/**
* View不在例外クラス
*
* Viewが見つからないときに投げる例外
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class MissingViewException extends UserDefinedException {
  /**
  * コンストラクタ
  *
  * エラーコード:40001 タイトル:'View不在例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 40001;
    $this->message = 'View不在例外';
    $this->subMessage = $subMessage;
  }
}
