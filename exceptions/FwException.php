<?php
namespace FW\Exception;

/**
* フレームワーク基底例外クラス
*
* 本フレームワークの（操作すべきものに対しての）基底例外クラス
*
* プロパティを追加
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Exception
*/
class FwException extends \Exception {
  /**
  * サブメッセージ
  * @access protected
  * @var string
  */
  protected string $subMessage = "";

  /**
  * コンストラクタ
  *
  * エラーコード:1001 タイトル:フレームワーク例外
  * @access public
  * @param string $subMessage
  */
  public function __construct($subMessage){
    $this->code = 1001;
    $this->message = 'フレームワーク例外';
    $this->subMessage = $subMessage;
  }

  /**
  * サブメッセージ取得
  *
  * $subMessage Getter
  * @access public
  * @return string $subMessage
  */
  public function getSubMessage():string { return $this->subMessage; }
}
