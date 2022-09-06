<?php
namespace FW\Foundation;

require_once(__DIR__."/Value.php");

/**
* Jsonデータオブジェクト
*
* Jsonとして、標準出力したいときに使うデータオブジェクト
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Foundation
*/
class JsonValue extends Value
{
  /**
  * JSONにエンコードした状態の文字列格納
  * @access private
  * @var string $jsonString
  */
  private string $jsonString = "";

  /**
	* jsonエンコード及び保管
	*
	* 引数に与えられた配列をjsonエンコードして、保管する
	* @access public
	* @param array
	*/
  public function json(array $json):void
  {
    $this->jsonString = json_encode($json);
  }

  /**
	* json文字列保管
	*
	* 既にエンコードされた文字列を保管する
	* @access public
	* @param string
	*/
  public function jsonString(string $jsonString):void
  {
    $this->jsonString = $jsonString;
  }

  /**
	* json取得
	*
	* 保管したjsonを取得する
	* @access public
	* @return string
	*/
  public function getJson():string
  {
    return $this->jsonString;
  }
}
