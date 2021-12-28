<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

/**
* Controller不在例外クラス
*
* Controllerが見つからないときに投げる例外
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class MissingControllerException extends UserDefinedException {
  /**
  * コンストラクタ
  *
  * エラーコード:20001 タイトル:Controller不在例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 20001;
    $this->message = 'Controller不在例外';
    $this->subMessage = $subMessage;
  }
}
