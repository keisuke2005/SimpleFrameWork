<?php
namespace FW\Library;

require_once(__DIR__."/../utils/Session.php");

/**
* 認証インターフェース
*
* Controllerに継承し、CheckSessionで検証する。
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
interface Authenticatable
{
	/**
	* 認証機能適用
	*
	* trueだと適用、falseだと適用しない
	* @abstract
	* @access public
	* @return bool
	*/
	public function is_auth_required();

	/**
	* セッションキー設定
	*
	* returnにキー名を設定
	* @abstract
	* @access public
	* @return string
	*/
	public function get_auth_key();
}

/**
* 認証チェックトレイト
*
* Controllerに継承し、CheckSessionで検証する。
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
trait CheckSession
{
	/**
	* 認証チェックブリッジ
	*
	* _check_authenticatedに$thisを渡したい為
	* @access public
	* @param string $not_loggedin_url
	* @param string $loggedin_url
	* @return void
	*/
	public function check_authenticated(string $not_loggedin_url,string $loggedin_url): void
	{
		$this->_check_authenticated($this,$not_loggedin_url,$loggedin_url);
	}

	/**
	* 認証チェック
	*
	* Authenticatableを継承したControllerオブジェクトに対して、認証チェックを行う
	* @access public
	* @param Authenticatable $instance
	* @param string $not_loggedin_url
	* @param string $loggedin_url
	* @return void
	*/
	private function _check_authenticated(Authenticatable $instance,string $not_loggedin_url,string $loggedin_url): void
	{
		Session::start();

		if($this->is_auth_required() && ! Session::is_login($this->get_auth_key()))
		{
			Session::redirect($not_loggedin_url);
		}

		if(! $this->is_auth_required() && Session::is_login($this->get_auth_key()))
		{
			Session::redirect($loggedin_url);
		}
	}
}
