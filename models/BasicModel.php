<?php
namespace FW\Foundation;

require_once(__DIR__."/../models/Model.php");

/**
* BasicModel Class
*
* BasicControllerに付随するModel
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
*/
abstract class BasicModel extends Model
{
	/**
	* ビジネスロジック部
	*
	* Modelのメインロジック
	*
	* 実装はユーザ定義ファイルで行う。
	* @access protected
	* @param Request $request
	* @param Value $value
	*/
	abstract public function process(Request $request,Value $value);
}
