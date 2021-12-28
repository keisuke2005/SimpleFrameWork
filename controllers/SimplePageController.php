<?php
namespace FW\Foundation;

require_once(__DIR__."/../controllers/Controller.php");
require_once(__DIR__."/../views/HtmlView.php");

/**
* SimplePageController Class
*
* サーバサイドでの処理をせず、静的なページを返すController
*
* １つの命名規則でController,Viewの2ファイルを作成することを想定する。
*
* Controllerを継承
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @see Controller
* @package FW\Foundation
*/
abstract class SimplePageController extends Controller
{
	/**
	* Modelオブジェクト処理
	*
	* Modelを生成せずに、Valueのみ返却する。
	* @access protected
	* @param Request $request
	* @param Value $value
	* @return Value
	*/
	protected function model(Value $value): Value
	{
		$this->logger->info("Start Function");
		$this->logger->info("End Function");
		return $value;
	}

	/**
	* Viewオブジェクト処理
	*
	* Viewオブジェクト処理概要
	** Viewオブジェクト生成
	** View::flushを実行
	* @access protected
	* @param Request $request
	* @param Value $value
	* @return Value
	*/
	protected function view(Value $value): Value
	{
		$this->logger->info("Start Function");
		$view = $this->get_view();
		$this->logger->info("End Function");
		return $view->flush($value);
	}

	/**
	* Modelオブジェクト生成
	*
	* 生成しない。
	* @access protected
	* @param string $model_name
	* @return null
	* @see BasicModel
	* @todo てかこの関数、呼び出されてないんじゃね？ｗ
	*/
	protected function get_model(?string $model_name = null): ?Model
	{
		$this->logger->info("Start Function");
		$this->logger->info("End Function");
		return null;
	}

	/**
	* Viewオブジェクト生成
	*
	* Viewオブジェクト生成して返却する。
	*
	* 原則、FW内のHtmlViewを生成する。
	*
	* この部分だけオーバーライドできるように切り出している。
	* @access protected
	* @return View
	* @see HtmlView
	*/
	protected function get_view(): View
	{
		$this->logger->info("Start Function");
		$this->logger->info("End Function");
		return new HtmlView($this->logger,$this->request,$this->mvc_info,$this->route);
	}
}
