<?php
namespace FW\Foundation;

require_once(__DIR__."/../core/MvcCore.php");
require_once(__DIR__."/../models/Model.php");
require_once(__DIR__."/../views/View.php");
require_once(__DIR__."/../exceptions/UserDefinedException.php");
require_once(__DIR__."/../exceptions/MissingModelException.php");
require_once(__DIR__."/../exceptions/MissingViewException.php");
require_once(__DIR__."/../exceptions/MissingFunctionException.php");
require_once(__DIR__."/../exceptions/ExceptionViewer.php");

use FW\Exception\UserDefinedException;
use FW\Exception\MissingModelException;
use FW\Exception\MissingViewException;
use FW\Exception\MissingFunctionException;
use FW\Exception\ExceptionViewer;

/**
* Controller Class
*
* 本フレームワークの基底のController
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @package FW\Foundation
*/
abstract class Controller extends MvcCore
{
	/**
	* クラス処理実行
	*
	* 値の受渡を行いながら、ModelとViewを実行
	* @access protected
	* @return string
	*/
	protected function execute(): void
	{
		try
		{
			$this->logger->info("Start Function");
			$value = $this->value();
			$_value = $this->model($value);
			$result = $this->view($_value);
			$this->logger->info("End Function");
		}
		catch (\UserDefinedException $e)
		{
			/*
			ユーザ定義部分でのユーザの実装ミス等を補足する為のもの
			outputは一応ちゃんとした画面になってる
			*/
			$this->logger->info("Catch by UserDefinedException:".$e->getMessage().":".$e->getSubMessage());
			ExceptionViewer::output($e);
		}

		catch (\LogicException $e)
		{
			/*
			FW内で関数の使い方や、事前に予想できるものに対してエラーハンドリングし、
			FW作成者にロジックエラーを伝える旨のものなので、雑にエラーを吐き出してOK
			*/
			$this->logger->info("Catch by LogicException:".$e->getMessage());
			ExceptionViewer::output($e);
		}
		catch (\Throwable $e)
		{
			$this->logger->info("Catch by Throwable:".$e->getMessage());
			ExceptionViewer::output($e);
		}
	}

	/**
	* Valueオブジェクト処理
	*
	* Value継承のShareオブジェクトを生成する
	* @access protected
	* @return Value
	* @see Value
	*/
	protected function value(): Value
	{
		$this->logger->info("Start Function");
		$value_name = $this->get_value_name();
		$this->require_file($this->get_value_path($value_name));
		$class = "FW\\Foundation\\".$value_name;
		$value = new $class();
		$this->logger->info("End Function");
		return $value;
	}

	/**
	* Modelオブジェクト処理
	*
	* Modelオブジェクト生成して処理を実行する。
	*
	* Modelオブジェクトの選定及び生成は子クラスに委ねる。
	* @abstract
	* @access protected
	* @param Value $value
	* @throws MissingModelException Modelがない場合
	* @throws MissingFunctionException 実行するfunction(Model内)がない場合
	*/
	abstract protected function model(Value $value);

	/**
	* Viewオブジェクト処理
	*
	* Viewオブジェクト生成して処理を実行する。
	*
	* Viewオブジェクトの選定及び選定は子クラスに委ねる
	* @abstract
	* @access protected
	* @param Value $value
	* @throws MissingViewException
	*/
	abstract protected function view(Value $value);

	/**
	* Value継承クラス名前取得
	*
	* 命名規則Valueがない場合のデフォルトValueになるもの。
	* @access protected
	* @return string
	*/
	protected function get_value_name(): string
	{
		$this->logger->info("Start Function");
		$name = "Share";
		$this->logger->info("End Function");
		return $name;
	}

	/**
	* ShareClassファイルパス取得
	*
	* 命名規則Valueがない場合のデフォルトValueになるもの。
	* @access protected
	* @return string
	*/
	protected function get_value_path(string $value_name): string
	{
		$this->logger->info("Start Function");
		$path = __DIR__."/../values/{$value_name}.php";
		$this->logger->info("End Function");
		return $path;
	}

	/**
	* ファイル読み込み
	*
	* require_onceで読みこみ且つ、エラー制御
	* @access protected
	* @param string $filename
	* @return bool
	* @throws UserDefinedException この関数はやや共通関数ぎみで上位で補足し、また違うExceptionで分岐させる為UserDefinedExceptionでthrow
	*/
	protected function require_file(string $filename): void
	{
		$this->logger->info("Start Function");
		if(! file_exists($filename)) throw new UserDefinedException("ファイルが存在しません。({$filename})");
		$result = require_once($filename);
		if($result === false) throw new UserDefinedException("ファイルの読み込みに失敗しました。({$filename})");
		$this->logger->info("End Function");
	}

	/**
	* 起動
	*
	* Controllerクラスを継承するクラスを実行する
	*
	* 順序を制限する為、外部から実行できるのは基本的にはこのクラスのみ
	* @access public
	* @static
	* @param Controller $controller
	* @return void
	*/
	public static function run(Controller $controller): void
	{
		$controller->execute();
	}
}
