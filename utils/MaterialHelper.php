<?php
namespace FW\Library;

use FW\Foundation\MvcInfo;

/**
* データベースアクセスオブジェクト
*
* データベースオブジェクト管理及びSQL発行
*
* 本フレームワーク方針として、生のSQLを書き、不必要にラップしない
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
class MaterialHelper
{
  const MATERIAL = "materials";
  private string $aplpath;


  function __construct(MvcInfo $mvc)
  {
    $this->aplpath = $mvc->get_application_path();
  }

  public function get($path):string
  {
    $open = fn($str) => $str;
    $target = "{$this->aplpath}/{$open(Self::MATERIAL)}/{$path}";
    return $target;
  }
}
