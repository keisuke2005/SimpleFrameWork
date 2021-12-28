<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

/**
* Model不在例外クラス
*
* Modelが見つからないときに投げる例外
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class MissingModelException extends UserDefinedException {
  /**
  * コンストラクタ
  *
  * エラーコード:30001 タイトル:Model不在例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 30001;
    $this->message = 'Model不在例外';
    $this->subMessage = $subMessage;
  }
}
