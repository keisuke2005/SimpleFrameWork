<?php
namespace FW\Routing;

require_once("Route.php");
require_once(__DIR__."/../values/Request.php");
require_once(__DIR__."/../values/MvcInfo.php");
require_once(__DIR__."/../utils/Logger.php");
require_once(__DIR__."/../core/Core.php");
require_once(__DIR__."/../utils/Judgement.php");
require_once(__DIR__."/../exceptions/ExceptionViewer.php");
require_once(__DIR__."/../exceptions/MissingControllerException.php");
require_once(__DIR__."/../exceptions/MissingRouteDefinitionFileException.php");
require_once("./configs/Config.php");

use FW\Foundation\Core;
use FW\Foundation\Controller;
use FW\Library\Judgement;
use FW\Exception\ExceptionViewer;
use FW\Exception\UserDefinedException;
use FW\Exception\MissingRouteDefinitionFileException;
use FW\Exception\MissingControllerException;

/**
* Router Class
* 本フレームワークのアクセスURLに対してのルーティングを担うクラス
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @package FW\Routing
*/
abstract class Router extends Core
{
	/**
	* Routeオブジェクト
	* @access private
	* @var Route
	* @see Route
	*/
	private Route $decide_route;

	/**
	* Controller継承オブジェクト
	* @access private
	* @var Controller
	* @see Controller
	*/
	private Controller $controller;

	/**
	* Route保管用オブジェクト
	* @access protected
	* @static
	* @var Route[]
	* @see Route
	*/
	public static array $routes = [];

	/**
	* $application_path getter
	*
	* 継承先でアプリケーションパスの設定する、実装義務がある。
	* @access protected
	* @abstract
	*/
	abstract public static function get_application_path();

	/**
	* ルーティング定義ファイル取得
	*
	* ルート定義ファイルの位置が変わればオーバライドして使用する。
	* @access protected
	* @return string
	*/
	protected static function get_route_define_files(): string
	{
		return "/configs/routes/*.php";
	}

	/** ログパス取得
	*
	* ログのパスを返す
	*
	* デフォルトは /appname/logs/application.log とするが、オーバーライドで書き変え可
	* @access protected
	* @return string
	*/
	public static function get_log_path(): string
	{
		return "/logs/application.log";
	}

	/**
	* ルーティング定義ファイル取得
	*
	* アプリケーションパスと連結したルーティングXMLの絶対パスを返す
	* @access protected
	* @return string
	*/
	protected function get_route_file(): string
	{
		return $this->mvc_info->get_application_path().static::get_route_define_files();
	}
	/**
	* ルート定義
	*
	* XMLのルーティング定義をRouteオブジェクトとして配列化する。
	* @access protected
	* @return array<Route>
	* @throws MissingRouteDefinitionFileException ルーティング定義ファイルまでのパスが不正または、ファイルが１つもない場合
	*/
	protected function defined_route():array
	{
		$path = $this->get_route_file();
		$routes = false;
		if(! file_exists(dirname($path)))
		{
			throw new MissingRouteDefinitionFileException("ルート定義ファイルまでのディレクトリ構成が不正です。({$path})");
		}
		$files = array_filter(glob($path), 'is_file');
		if(count($files) < 1)
		{
			throw new MissingRouteDefinitionFileException("ルート定義ファイルが存在しません。({$path})");
		}

		foreach(glob($path) as $file)
		{
			if(is_file($file))
			{
				require_once($file);
			}
		}


		return Router::$routes;
	}

	/**
	* デフォルトルート設定
	*
	* デフォルトルート用のRouteオブジェクトを返す。
	*
	* 継承で変更しても良い。
	* @access protected
	* @return Route $default
	*/
	protected function default_route(): Route
	{
		$this->logger->info("Start Function");
		$default = new Route(
			"/default",
			"GET",
			"Default",
			"Default",
			"process"
		);
		$this->logger->info("End Function");
		return $default;
	}

	/**
	* コア処理実行
	*
	* 本クラスのコア処理
	** ベースパスを設定する。
	** リクエストURIからルーティングを決定する。
	** 適切なControllerを選択する。
	** Controllerを実行する。
	* @access protected
	* @return void
	*/
	protected function execute():void
	{
		$this->logger->info("Start Function");
		$this->decide_route = $this->decide_route();
		$this->controller = $this->grab_class();
		$this->exec_controller($this->controller);
		$this->logger->info("End function");
	}

	/**
	* クラス選択
	*
	* Controllerを選択し、インスタンスを生成してから返す
	* @access protected
	* @return Controller $con
	*/
	protected function grab_class(): Controller
	{
		$this->logger->info("Start Function");
		$controller = $this->decide_route->class_name(Route::CONTROLLER);
		$this->require_controller($this->decide_route->file_location($this->mvc_info,Route::CONTROLLER));
		$con = new $controller(
			$this->logger,
			$this->request,
			$this->mvc_info,
			$this->decide_route
		);
		$this->logger->info("End Function");
		return $con;
	}

	/**
	* Controller実行
	*
	* Controller::runで実行する。
	* @access protected
	* @param Controller $controller
	* @return void
	*/
	protected function exec_controller(Controller $controller): void
	{
		$this->logger->info("Start Function");
		$controller::run($controller);
		$this->logger->info("End Function");
	}

	/**
	* Controller読み込み
	*
	* ユーザ定義Controllerクラスのファイルを読みこむ
	* @access private
	* @param string $controller_path
	* @return void
	* @throws MissingControllerException Controllerのファイルがない場合
	*/
	private function require_controller(string $controller_path):void
	{
		$this->logger->info("Start Function");
		if(! file_exists($controller_path))
		{
			throw new MissingControllerException("Controllerファイルが存在しません。({$controller_path})");
		}
		require_once($controller_path);
		$this->logger->info("End Function");
	}

	/**
	* ルート決定
	*
	* リクエストURIとRouteオブジェクトを比較し、一致したRouteオブジェクトを返却する。
	* @access protected
	* @return Route $decide
	*/
	protected function decide_route(): Route
	{
		$this->logger->info("Start Function");
		$decide = $this->default_route();
		foreach($this->defined_route() as $route)
		{
			if($route->compare($this->request,$this->mvc_info))
			{
				$route->extract_args($this->mvc_info->get_apl_url_path(),$this->mvc_info->get_request_url_path());
				$decide = $route;
				break;
			}
		}
		$this->logger->info("End Function");
		return $decide;
	}

	/**
	* Route定義追加
	*
	* Route::define,Route::mapで生成されたRouteオブジェクトの配列をまとめる
	*
	* Routerのstatic部分にまとめる用の変数が存在するので、それを利用。
	* @access public
	* @static
	* @param array $routes
	* @return void
	* @throws InvalidArgumentException $routesが不正な配列形式のとき
	*/
	public static function push(array ...$routes):void
	{
		if(! Judgement::isUnifiedArrayType($routes,"array"))
		{
			throw new \InvalidArgumentException("不正な配列の形式です。");
		}
		Router::$routes = array_reduce($routes,function($x,$y){
			// この配列の型は必ずRoute[]になるはずなので、違っていればLogicException系でthrow
			if(! Judgement::isUnifiedArrayType($x,"FW\Routing\Route") || ! Judgement::isUnifiedArrayType($y,"FW\Routing\Route"))
			{
				throw new \InvalidArgumentException("二次元目はRouteオブジェクトでなければなりません。");
			}
			return array_merge($x,$y);
		},[]);
	}

	/**
	* 起動
	*
	* Routerクラスを継承するクラスを実行する
	* 順序を制限する為、外部から実行できるのは基本的にはこのクラスのみ
	* @access public
	* @static
	* @param Router $router
	* @return void
	*/
	public static function routing(Router $router)
	{
		try
		{
			$router->execute();
		}
		catch (UserDefinedException $e)
		{
			$router->logger()->info("Catch by UserDefinedException:".$e->getMessage().":".$e->getSubMessage());
			ExceptionViewer::output($e);
		}
		catch (\Throwable $e)
		{
			$router->logger()->info("Catch by Throwable:".$e->getMessage());
			ExceptionViewer::output($e);
		}
	}
}
