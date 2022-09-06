<?php
namespace FW\Foundation;

require_once(__DIR__."/../controllers/Controller.php");
require_once(__DIR__."/../views/HtmlView.php");

/**
* BasicController Class
*
* 基本的なMVCで動作させるController
*
* １つの命名規則でController,Model,Viewの3ファイルを作成することを想定する。
*
* Controllerを継承
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @see Controller
*/
abstract class BasicController extends Controller
{
	/**
	* Modelオブジェクト処理
	*
	* Modelオブジェクト処理概要
	** Modelオブジェクト生成
	** Model::processを実行
	* @access protected
	* @param Request $request
	* @param Value $value
	* @return Value
	* @throws MissingModelException get_model内に定義
	* @throws MissingFunctionException ファンクションが見つからない場合
	*/
	protected function model(Request $request,Value $value): Value
	{
		$this->logger->info("Start Function");
		$model = $this->get_model();
		$fnc = is_null($this->route->get_fnc()) ? "process" : $this->route->get_fnc();
		if(! method_exists($model,$fnc)) throw new MissingFunctionException("Model内にファンクションが見つかりません。(".get_class($model)."::".$fnc.")");
		$_value = $model->$fnc($request,$value);
		$this->logger->info("End Function");
		return $_value;
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
	protected function view(Request $request,Value $value): Value
	{
		$this->logger->info("Start Function");
		$view = $this->get_view();
		$this->logger->info("End Function");
		return $view->flush($request,$value);
	}

	/**
	* Modelオブジェクト生成
	*
	* BasicModelを継承したユーザ定義Modelオブジェクト生成して返却する。
	*
	* 原則、Controllerと同じ名前のものを命名規則として生成する。
	*
	* この部分だけオーバーライドできるように切り出している。
	* @access protected
	* @param string $model_name
	* @return Model
	* @see BasicModel
	* @throws MissingModelException
	*/
	protected function get_model(?string $model_name = null): Model
	{
		$this->logger->info("Start Function");
		$model_name = $this->route->class_name($this->route::MODEL);
		try
		{
			$this->require_file($this->route->file_location($this->mvc_info,$this->route::MODEL));
		}
		catch (\UserDefinedException $e)
		{
			throw new MissingModelException($e->getSubMessage());
		}
		$model = new $model_name($this->logger,$this->request,$this->mvc_info,$this->route);
		$this->logger->info("End Function");
		return $model;
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
