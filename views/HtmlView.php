<?php
namespace FW\Foundation;

require_once(__DIR__."/../views/View.php");
require_once(__DIR__."/../exceptions/MissingViewException.php");

use FW\Routing\Route;
/**
* HtmlView Class
*
* Basic,SimplePageに付随するView
*
* HtmlView::flushにより、ユーザ定義ViewファイルはHtmlのみを記述するような書き方を実現できる。
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
*/
class HtmlView extends View {
	/**
	* Valueオブジェクト
	* @access protected
	* @var Value
	* @see Value
	*/
	protected Value $value;

	/**
	* cache制御
	*
	* css,js読みこむ際に末尾に時間を付与し、cacheを蓄積させないようにする。
	* @access protected
	* @return bool
	*/
	protected function is_nocache(): bool {return true;}

	/**
	* jsファイル取得
	*
	* 標準で読みこむjsライブラリ群を定義しておく。
	* オーバーライドでカスタマイズしてください。
	* @access protected
	* @return string[]
	*/
	public function get_js()
	{
		return array(
			"default.js"
			,"jquery-3.6.0.min.js"
			,"bootstrap.bundle.min.js"
		);
	}

	/**
	* cssファイル取得
	*
	* 標準で読みこむcssライブラリ群を定義しておく。
	* オーバーライドでカスタマイズしてください。
	* @access protected
	* @return string[]
	*/
	public function get_css()
	{
		return array(
			"default.css"
			,"bootstrap.min.css"
		);
	}

	/**
	* ファイル読み込み
	*
	* css、jsをhtmlで読みこむ形にする
	* @access protected
	* @param string $type
	* @param array $files
	* @return void
	*/
	protected function loader(string $type,array $files)
	{
		$suffix = "";
		if($this->is_nocache()) $suffix = "?".time();

		foreach($files as $file){
			echo $this->tag($type,"/".$type."/".$file.$suffix);
		}
	}

	/**
	* タグ生成
	*
	* css、jsをhtmlで読みこめるタグを生成する
	* @access protected
	* @param string $type
	* @param string $uri
	* @return string
	*/
	protected function tag(string $type,string $uri):string
	{
		$tag = "";
		switch ($type)
		{
			case 'js':
				$tag = '<script src="'
					.$this->mvc_info()->get_apl_url_path().$uri
					.'"></script>';
				break;
			case 'css':
				$tag = '<link rel="stylesheet" href="'
					.$this->mvc_info()->get_apl_url_path().$uri.'">';
				break;
			default:
				$tag = "";
		}
		return $tag;
	}

	/**
	* 出力
	*
	* ユーザ定義領域のviewsディレクトリの命名規則に沿ったユーザ定義View継承クラスを出力する。
	*
	* これにより、phpとhtmlの混在を防ぐ。
	* ちなみに経緯としては、phpのヒアドキュメントとかだと、IDEなどでhtmlの色分けをしてくれなくなるから。
	* @access public
	* @param Request $request
	* @param Value $value
	* @return Value
	*/
	public function flush(Value $value): Value
	{
		$this->value = $value;
		$this->flush_html($this->mvc_info(),$this->route());
		return $this->value;
  }

	/**
	* タイトル
	*
	* htmlの/<title>をreturnすれば出力してくれるようになる。
	* @access public
	* @return string
	*/
	public function get_title()
	{
		return "";
	}

	/**
	* 文字コード
	*
	* htmlの文字コードを指定するところ。returnすればそれになる。
	* @access public
	* @return string
	*/
	public function get_charaset()
	{
		return "UTF-8";
	}

	/**
	* lang
	*
	* htmlのlangを指定するところ。returnすればそれになる。
	* @access public
	* @return string
	*/
	public function get_lang()
	{
		return "ja";
	}

	/**
	* 出力html検索
	*
	* ユーザ定義領域のviewsディレクトリの命名規則に沿ったユーザ定義View継承クラスのファイルを探して、読みこむ。
	* @access public
	* @param array $dir
	* @param string $file
	* @return Value
	*/
	public function flush_html(MvcInfo $mvc,Route $route):void
	{
		$path = $route->file_location($mvc,$route::VIEW);
		if(! file_exists($path)) throw new MissingViewException("ファイルが存在しません。({$path})");
		$result = require($path);
		if(! $result) throw new MissingViewException("ファイルの読み込みに失敗しました。({$path})");
	}

	public function flush_tags(array $dir,string $file):void
	{
		$path = "";
		if(! empty($dir) && array_search("",$dir) === false)
		{
			$path = implode("/",$dir)."/";
		}
		require("{$this->mvc_info()->get_application_path()}/views/{$path}{$file}");
	}
}
