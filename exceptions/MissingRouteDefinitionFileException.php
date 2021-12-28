<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

/**
* ルート定義ファイル不在例外クラス
*
* ルート定義ファイルが見つからないときに投げる例外
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class MissingRouteDefinitionFileException extends UserDefinedException {
  /**
  * コンストラクタ
  *
  * エラーコード:10002 タイトル:ルート定義ファイル不在例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 10002;
    $this->message = 'ルート定義ファイル不在例外';
    $this->subMessage = $subMessage;
  }
}
