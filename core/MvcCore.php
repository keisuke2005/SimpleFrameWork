<?php
namespace FW\Foundation;

require_once(__DIR__."/Core.php");

use FW\Library\Logger;
use FW\Routing\Route;

/**
* Mvcデータ群
*
* Controller,Model,Viewはこれを継承する
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @package FW\Foundation
*/
abstract class MvcCore extends Core
{
  /**
  * MvcInfoオブジェクト
  * @access protected
  * @var Route
  * @see Route
  */
  protected Route $route;

  /**
  * コンストラクタ
  *
  * Coreのコンストラクタ呼び出しとRouteを格納
  * @access public
  * @param Logger $logger
  * @param Request $request
  * @param MvcInfo $mvc_info
  * @param Route $route
  * @return void
  */
  public function __construct(Logger $logger,Request $request ,MvcInfo $mvc_info,Route $route)
	{
    parent::__construct($logger,$request,$mvc_info);
		$this->logger->info("Start Function");
		$this->route = $route;
		$this->logger->info("End Function");
	}

  /**
  * $route Getter
  * @access protected
  * @return Route
  */
  protected function route():Route {return $this->route;}
}
