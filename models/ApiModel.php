<?php
namespace FW\Foundation;

require_once(__DIR__."/../models/Model.php");

/**
* ApiModel Class
*
* ApiControllerに付随するModel
*
* これを継承したのちHttpMethod系列のインターフェースをimplementsして使用する。
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @package FW\Foundation
*/
abstract class ApiModel extends Model
{
}
