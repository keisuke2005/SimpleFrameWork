<?php
namespace FW\Foundation;

require_once(__DIR__."/../controllers/Controller.php");
require_once(__DIR__."/../views/JsonView.php");

/**
* ApiController Class
*
* APIのエンドポイントを作る際のController
*
* １つの命名規則でController,Model想定し、ViewはJsonを返す定形とする。
*
* Controllerを継承
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @see Controller
*/
abstract class ApiController extends Controller
{
	/**
	* Modelオブジェクト処理
	*
	* Modelオブジェクト処理概要
	** Modelオブジェクト生成
	** ApiController::methodにModelオブジェクトを引数にして引き継ぐ
	* @access protected
	* @param Request $request
	* @param Value $value
	* @return Value
	* @throws MissingModelException
	* @throws MissingFunctionException
	*/
	protected function model(Value $value): Value
	{
		$this->logger->info("Start Function");
		$model = $this->get_model();
		$this->logger->info("End Function");
		$fnc = is_null($this->route->get_fnc()) ? strtolower($this->request->get_request_method()) : $this->route->get_fnc();
		if(! method_exists($model,$fnc))
		{
			throw new MissingFunctionException("Model内にファンクションが見つかりません。(".get_class($model)."::".$fnc.")");
		}
		return $model->$fnc($value);
	}

	/**
	* Viewオブジェクト処理
	*
	* Viewオブジェクト処理概要
	** Viewオブジェクト生成
	** View::jsonを実行
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
		return $view->json($value);
	}

	/**
	* Modelオブジェクト生成
	*
	* ApiModel
	*
	* 原則、Controllerと同じ名前のものを命名規則として生成する。
	*
	* この部分だけオーバーライドできるように切り出している。
	* @access protected
	* @param string $model_name
	* @return Model
	* @see ApiModel
	* @throws MissingModelException Controller::require_fileがUserDefinedExceptionをスローしたときにcatchして再スロー求む
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
		$this->logger->info("End Function");
		return new $model_name($this->logger,$this->request,$this->mvc_info,$this->route);
	}

	/**
	* Viewオブジェクト生成
	*
	* Viewオブジェクト生成して返却する。
	*
	* 原則、FW内のJsonViewを生成する。
	*
	* この部分だけオーバーライドできるように切り出している。
	* @access protected
	* @return View
	* @see JsonView
	* @package FW\Foundation
	*/
	protected function get_view(): View
	{
		$this->logger->info("Start Function");
		$this->logger->info("End Function");
		return new JsonView($this->logger,$this->request,$this->mvc_info,$this->route);
	}

	/**
	* Value継承クラス名前取得
	*
	* JsonValueにオーバライド
	* @access protected
	* @return string
	*/
	protected function get_value_name(): string
	{
		$this->logger->info("Start Function");
		$name = "JsonValue";
		$this->logger->info("End Function");
		return $name;
	}
}
