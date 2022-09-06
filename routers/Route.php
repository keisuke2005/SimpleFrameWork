<?php
namespace FW\Routing;

require_once("Router.php");
require_once(__DIR__."/../exceptions/RouteDefinitionException.php");

use FW\Foundation\MvcInfo;
use FW\Foundation\Request;
use FW\Exception\MissingRouteDefinitionFileException;

/**
 * Router Classで扱うRoute詳細オブジェクト
 * @access public
 * @author keisuke <ukei2021@gmail.com>
 * @copyright MezzoDay Corporation All Rights Reserved
 * @version 1.0
 * @see Router
 * @package FW\Routing
 */
class Route{
	/**
	* プレフィックスURI
	* @var string
	*/
	private ?string $pfxuri;

	/**
	* URI
	* @var string
	*/
	private ?string $uri;

	/**
	* リクエストメソッド
	* @var string
	*/
	private ?string $mth;

	/**
	* Controller名
	* @var string
	*/
	private ?string $con;

	/**
	* MVC名
	* @var string
	*/
	private ?string $mvc;

	/**
	* ファンクション名
	* @var string
	*/
	private ?string $fnc;

	const CONTROLLER = "Controller";
	const MODEL = "Model";
	const VIEW = "View";

	private static array $keys = array("uri","mth","con","mvc","fnc","pfxuri");

	/**
	* パスパラメータ
	* @var string
	*/
	private array $path_parameters = [];

	/**
	* 利用可能メソッド群
	* @var string[]
	*/
	private static $mths = array("GET","POST","PUT","DELETE");

	/**
	* インスタンス生成時にリクエストURIパスとController名を引数として渡す
	* @access public
	* @param string $path
	* @param string $controller
	* @return void
	*/
	function __construct(?string $uri,?string $mth,?string $con,?string $mvc,?string $fnc,?string $pfxuri = null)
	{
		$this->uri = $uri;
		$this->mth = $mth;
		$this->con = $con;
		$this->mvc = $mvc;
		$this->fnc = $fnc;
		$this->pfxuri = $pfxuri;
	}

	/**
	* パスパラメータ用 Getter
	* @access public
	* @param $method
	* @param $args
	* @return mixed
	*/
	public function __call($method, $args)
	{
		if(strpos($method,'get_') === 0)
		{
			$prop_name = str_replace('get_', '', $method);
			if(isset($this->path_parameters[$prop_name])) return $this->path_parameters[$prop_name];
		}
		// 見つからなかったらエラーということで。
		throw new LogicException("存在しないpropertyのgetterを呼び出そうとしています。(呼出要求property{$method})");
	}

	/**
	* $pfxuri Getter
	* @access public
	* @return string
	*/
	public function get_pfxuri(): ?string { return $this->pfxuri; }

	/**
	* $uri Getter
	* @access public
	* @return string
	*/
	public function get_uri(): ?string { return $this->uri; }

	/**
	* $mth Getter
	* @access public
	* @return string
	*/
	public function get_mth(): ?string { return $this->mth; }

	/**
	* $con Getter
	* @access public
	* @return string
	*/
	public function get_con(): ?string { return $this->con; }

	/**
	* $mvc Getter
	* @access public
	* @return string
	*/
	public function get_mvc(): ?string { return $this->mvc; }

	/**
	* $fnc Getter
	* @access public
	* @return string
	*/
	public function get_fnc(): ?string { return $this->fnc; }


	/**
	* $pfxuri Setter
	* @access public
	* @param string|null $pfxuri
	* @return void
	*/
	private function set_pfxuri(?string $pfxuri): void { $this->pfxuri = $pfxuri; }

	/**
	* $uri Setter
	* @access public
	* @param string|null $uri
	* @return void
	*/
	private function set_uri(?string $uri): void { $this->uri = $uri; }

	/**
	* $mth Setter
	* @access public
	* @param string|null $mth
	* @return void
	*/
	private function set_mth(?string $mth): void { $this->mth = $mth; }

	/**
	* $con Setter
	* @access public
	* @param string|null $con
	* @return void
	*/
	private function set_con(?string $con): void { $this->con = $con; }

	/**
	* $mvc Setter
	* @access public
	* @param string|null $mvc
	* @return void
	*/
	private function set_mvc(?string $mvc): void { $this->mvc = $mvc; }

	/**
	* $fnc Setter
	* @access public
	* @param string|null $fnc
	* @return void
	*/
	private function set_fnc(?string $fnc): void { $this->fnc = $fnc; }



	/**
	* 経路判定
	*
	* 自信のインスタンスのURIパスと与えられたURIパスを比較し、判定する。
	*
	* {}でくくられたパスパラメータにも対応
	* @access public
	* @param string $apl_url_path
	* @param string $request_url_path
	* @return bool
	*/
	public function compare(Request $request,MvcInfo $mvc_info): bool
	{
		// リクエストメソッド判定
		$mth = is_null($this->get_mth()) ? "GET" : strtoupper($this->get_mth());

		if($request->get_request_method() !== $mth) return false;

		// リクエストパスとオブジェクトパスを/区切りで分割
		$apl_url_path_array = explode("/",$mvc_info->get_apl_url_path().$this->get_uri());
		$request_url_path_array = explode("/",$mvc_info->get_request_url_path());

		// 分割した際の配列要素数が一緒でなければ、同じではないので、関数終了
		if(count($apl_url_path_array) != count($request_url_path_array)) return false;

		// ループして、同じ番号のところを比較し、マッチしているかを見る
		// ただし、{}で囲われてるものはパスパラメータとして、何が入っても良いので、スキップ
		for($i = 1;$i < count($request_url_path_array); $i++)
		{
			if($this->is_args($apl_url_path_array[$i])) continue;
			if($apl_url_path_array[$i] !== $request_url_path_array[$i]) return false;
		}
		return true;
	}

	/**
	* パスパラメータ判定
	*
	* ルーティング定義ファイルに記載されたURIの/区切り部分がパスパラメータとして定義しているかを判定する。
	* @access public
	* @param string $apl_url_path
	* @param string $request_url_path
	* @return bool
	*/
	private function is_args(string $word):bool
	{
		// {}で囲われていたら、パスパラメータ部分と判定
		return preg_match('/^\{.*\}$/u',$word) === 1;
	}


	/**
	* パスパラメータ抽出
	*
	* 定義したURIにパスパラメータ定義があれば{}内の文字列を変数文字列として、抽出格納する。
	* @access public
	* @param string $apl_url_path
	* @param string $request_url_path
	* @return bool
	* @throws LogicException リクエストパスが不正な場合
	*/
	public function extract_args(string $apl_url_path,string $request_url_path): void
	{
		$apl_url_path_array = explode("/",$apl_url_path.$this->get_uri());
		$request_url_path_array = explode("/",$request_url_path);
		for($i = 1;$i < count($request_url_path_array); $i++)
		{
			if(! $this->is_args($apl_url_path_array[$i])) continue;
			if(! isset($request_url_path_array[$i])) throw new LogicException("パスパラメータに対応するリクエストパスが存在しません。");
			$this->path_parameters[rtrim(ltrim($apl_url_path_array[$i],'{'),'}')] = $request_url_path_array[$i];
		}
	}

	/**
	* ルート定義
	*
	* Routeオブジェクトまたはmap関数で紐づける土台部分を作成する。
	* @access public
	* @param string[] $parts
	* @return Route
	*/
	public static function define(array $parts):Route
	{
		$uri = $mth = $con = $mvc = $fnc = $pfxuri = null;
		/*
		* ["uri:/login","con:DefaultController"]
		* 上記のような、配列をループさせて、さらに : で分割
		* :の左を変数名とし、:の右を値とする。
		*/
		foreach($parts as $p)
		{
			$keyvalue = explode(":",$p);
			// ルート定義ファイルのキーが間違ったものであれば、FWを使用して実装した者のバグとして扱う
			if(! in_array($keyvalue[0],Route::$keys,true))
			{
				throw new RouteDefinitionException("不正なルート定義キーを使用しています。(定義キー '{$keyvalue[0]}')");
			}
			$key = $keyvalue[0];
			$$key = $keyvalue[1];
		}
		// Routeオブジェクト生成。nullを許容しているので、気にせずに。
		$route = new Route($uri,$mth,$con,$mvc,$fnc,$pfxuri);
		return $route;
	}


	/**
	* ルート定義拡張
	*
	* defineで作成された未完成のRouteオブジェクトにmap関数に与えられた引数を使いメッシュ的に量産する。
	* @access public
	* @param array $routes_parts
	* @return Route[] $routes
	* @throws RouteDefinitionFileException ルート定義関連の例外をthrow
	*/
	public function map(array ...$routes_parts):array
	{
		$routes = array();
		// 可変で与えられた配列の引数をループし、それもまた配列なのでさらにループ
		foreach($routes_parts as $parts)
		{
			// $routes_partsの二次元目は配列であること
			if(! is_array($parts)) throw new RouteDefinitionException("");

			$instance = clone $this;
			// 二次元目は文字列（uri:/login）またはRouteオブジェクト
			$is_exists_child = false;
			foreach($parts as $p)
			{
				// Routeオブジェクトの場合
				if(gettype($p) !== "string" && get_class($p) === "FW\Routing\Route")
				{
					$is_exists_child = true;
					$routes[] = $instance->join($p);
					continue;
				}
				$keyvalue = explode(":",$p);

				// ルート定義ファイルのキーが間違ったものであれば、FWを使用して実装した者のバグとして扱う
				if(! in_array($keyvalue[0],Route::$keys,true))
				{
					throw new RouteDefinitionException("不正なルート定義キーを使用しています。(定義キー '{$keyvalue[0]}')");
				}
				if($keyvalue[0] === "uri" && ! is_null($instance->get_uri())){
					$keyvalue[1] = $instance->get_uri().$keyvalue[1];
				}
				$key = "set_".$keyvalue[0];
				$instance->$key($keyvalue[1]);
			}
			// Routeオブジェクトとして妥当かどうかを判断し、妥当ならreturnする配列に追加。
			if($is_exists_child) continue;
			if(Route::check($instance)) $routes[] = $instance;
		}
		return $routes;
	}

	/**
	* ルート定義合体
	*
	* defineとmapで合体して、１つのRouteオブジェクトを作成するときに、ルート定義を合体させるためのロジック
	* @access public
	* @param array $routes_parts
	* @return Route $child
	*/
	public function join(Route $child):Route
	{
		/*
		* uriは親がnullじゃなければ、親uri + 子uriとする
		* さらに子にpfxuriがあれば、それを先頭に付与する
		*/
		$uri = is_null($child->uri) ? $this->uri : $this->uri.$child->uri;
		if(! is_null($child->pfxuri)) $uri = $child->pfxuri.$uri;
		$child->set_uri($uri);

		// その他は子を優先にして、子が存在するなら子で上書き
		$child->set_mth(is_null($child->get_mth()) ? $this->get_mth() : $child->get_mth());
		$child->set_con(is_null($child->get_con()) ? $this->get_con() : $child->get_con());
		$child->set_fnc(is_null($child->get_fnc()) ? $this->get_fnc() : $child->get_fnc());
		$child->set_mvc(is_null($child->get_mvc()) ? $this->get_mvc() : $child->get_mvc());
		return $child;
	}

	/**
	* Routeオブジェクト妥当性チェック
	*
	* Routeオブジェクトのプロパティがルーティングする際に使えるかをチェックする。
	* @access public
	* @param Route $route
	* @return bool
	*/
	private static function check(Route $route):bool
	{
		/*
		* uri => 必須
		* mth => Route::$mthsに許容メソッド定義しているので、それに含まれるもののみ
		* con => 必須
		* mvc => 必須
		* fnc => Controllerでデフォルトを定義
		*/
		if(! is_null($route->get_mth()) && ! in_array(strtoupper($route->get_mth()),Route::$mths)) throw new RouteDefinitionException("ルート定義 mthの値が不正です。(定義キー '{$route->get_mth()}')");
		if(is_null($route->get_uri())) throw new RouteDefinitionException("ルート定義 uriは必須です。(定義キー '{$route->get_uri()}')");
		if(is_null($route->get_con())) throw new RouteDefinitionException("ルート定義 conは必須です。(定義キー '{$route->get_con()}')");
		if(is_null($route->get_mvc())) throw new RouteDefinitionException("ルート定義 mvcは必須です。(定義キー '{$route->get_mvc()}')");
		return true;
	}

	/**
	* クラス名取得
	*
	* MVC内でインスタンス化するときのクラス名取得
	* @access public
	* @param string $type
	* @return string
	*/
	public function class_name(string $type): string
	{
		switch ($type)
		{
			case Self::CONTROLLER :
				$value = $this->get_con();
				break;
			default:
				$value = $this->get_mvc();
				break;
		}
		$name = explode('/',$value)[substr_count($value,'/')].$type;
		return $name;
	}

	/**
	* クラスファイルパス取得
	*
	* MVC内でインスタンス化するときのクラスのファイルパス取得
	* @access public
	* @param MvcInfo $mvc
	* @param string $type
	* @return string
	*/
	public function file_location(MvcInfo $mvc,string $type): string
	{
		switch ($type){
			case Self::CONTROLLER :
				$value = $this->get_con();
				break;
			default:
				$value = $this->get_mvc();
				break;
		}
		$dir = strtolower($type)."s";
		$location = "{$mvc->get_application_path()}/{$dir}/{$value}{$type}.php";
		return $location;
	}
}
