<?php
namespace FW\Foundation;

use FW\Library\Logger;

/**
* 基底データ群
*
* RouterとMvcCoreはこれを継承する
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @package FW\Foundation
*/
abstract class Core
{
  /**
  * Loggerオブジェクト
  * @access protected
  * @var Logger
  * @see Logger
  */
  protected Logger $logger;

  /**
  * Requestオブジェクト
  * @access protected
  * @var Request
  * @see Request
  */
  protected Request $request;

  /**
  * MvcInfoオブジェクト
  * @access protected
  * @var MvcInfo
  * @see MvcInfo
  */
  protected MvcInfo $mvc_info;

  /**
  * コンストラクタ
  *
  * Controller,Model,Viewから共通的に参照する可能性があるオブジェクトを格納する。
  * @access public
  * @param Logger $logger
  * @param Request $request
  * @param MvcInfo $mvc_info
  * @return void
  */
  public function __construct(Logger $logger,Request $request ,MvcInfo $mvc_info)
	{
		$this->logger = $logger;
		$this->logger->info("Start Function");
		$this->request = $request;
		$this->mvc_info = $mvc_info;
		$this->logger->info("End Function");
	}

  /**
  * $logger Getter
  * @access protected
  * @return Logger
  */
  protected function logger():Logger {return $this->logger;}

  /**
  * $request Getter
  * @access protected
  * @return Request
  */
  protected function request():Request {return $this->request;}

  /**
  * $mvc_info Getter
  * @access protected
  * @return MvcInfo
  */
  protected function mvc_info():MvcInfo {return $this->mvc_info;}
}
