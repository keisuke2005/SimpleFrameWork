<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

/**
* ルート定義例外クラス
*
* ルート定義に不備があるときに投げる例外
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class RouteDefinitionException extends UserDefinedException {
  /**
  * コンストラクタ
  *
  * エラーコード:10001 タイトル:ルート定義例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 10001;
    $this->message = 'ルート定義例外';
    $this->subMessage = $subMessage;
  }
}
