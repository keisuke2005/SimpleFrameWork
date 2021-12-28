<?php
namespace FW\Library;

/**
* Session Class
*
* セッション操作ユーティリティ
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
class Session
{
	/**
	* セッションスタート
	*
	* session_start()実行
	* @access public
	* @static
	* @return void
	*/
	public static function start(): void
	{
		session_start();
	}

	/**
	* セッション洗い変え
	*
	* session_regenerate_id(TRUE)実行
	* @access public
	* @static
	* @return void
	*/
	public static function regenerate(): void
	{
		session_regenerate_id(TRUE);
	}

	/**
	* リダイレクト
	*
	* セッション切れ等に伴い、リダイレクトを想定
	* @access public
	* @static
	* @param string $redirect_to
	* @return void
	* @todo ユーティリティが少ないので、とりあえずここに書いているが、将来的にはもっと関連性の深いところへ移動させたい。
	*/
	public static function redirect(string $redirect_to)
	{
			header("Location:".$redirect_to);
			exit();
	}

	/**
	* セッション継続判定
	*
	* セッションが継続されているかどうかを判定する。
	* @access public
	* @static
	* @param string $key
	* @return bool
	*/
	public static function is_login(string $key)
	{
		return isset($_SESSION[$key]);
	}

	/**
	* セッション情報格納
	*
	* セッション情報を任意のkeyで格納する。
	* @access public
	* @static
	* @param string $key
	* @param string $value
	* @return void
	*/
	public static function regist(string $key,string $value)
	{
		$_SESSION[$key] = $value;
	}
}
