<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

/**
* 実行ファンクション不在例外クラス
*
* ルート定義で指定したModelのファンクションが見つからないときに投げる例外
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class MissingFunctionException extends UserDefinedException {
  /**
  * コンストラクタ
  *
  * エラーコード:50001 タイトル:実行ファンクション不在例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 50001;
    $this->message = '実行ファンクション不在例外';
    $this->subMessage = $subMessage;
  }
}
