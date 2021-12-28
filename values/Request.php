<?php
namespace FW\Foundation;

require_once(__DIR__."/Value.php");

/**
* MVC情報データオブジェクト
*
* MVCに関わる情報を保管するクラス
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Foundation
*/
class Request extends Value
{
	/**
	* @access private
	* @var Request 自身を管理するRequestオブジェクト格納変数
	* @see Request
	*/
	private static Request $request;

	protected ?string $server_addr;
	protected ?string $server_protocol;
	protected ?string $request_method;
	protected ?string $request_time;
	protected ?string $document_root;
	protected ?string $http_referer;
	protected ?string $http_user_agent;
	protected ?string $https;
	protected ?string $remote_port;
	protected ?string $remote_user;
	protected ?string $server_port;
	protected ?string $request_uri;
	protected ?string $context_document_root;
	protected ?string $context_prefix;

	private array $request_param = [];

	private function __construct()
	{
		$this->set_server_info();
		$this->set_request_parameter();
	}

	/**
	 * インスタンス取得
	 *
	 * 外部からこのクラスを扱うときは、こちらを利用してインスタンスを生成する。
	 * @access public
	 * @return Request
	 */
	public static function get(): Request
	{
		if(!isset(self::$request)) self::$request = new Request();
		return self::$request;
	}

	private function set_server_info()
	{
		$this->server_addr = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "";
		$this->server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : "";
		$this->request_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "";
		$this->request_time = isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : "";
		$this->document_root = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : "";
		$this->http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
		$this->http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
		$this->https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : "";
		$this->remote_port = isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : "";
		$this->remote_user = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : "";
		$this->server_port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : "";
		$this->request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";

		// ユーザディレクトリの場合必要なので追加
		$this->context_document_root = isset($_SERVER['CONTEXT_DOCUMENT_ROOT']) ? $_SERVER['CONTEXT_DOCUMENT_ROOT'] : "";
		$this->context_prefix = isset($_SERVER['CONTEXT_PREFIX']) ? $_SERVER['CONTEXT_PREFIX'] : "";
	}

	private function set_request_parameter()
	{

		$parameter = array();

		if(array_key_exists("Content-Type",getallheaders()) && getallheaders()["Content-Type"] === "application/x-www-form-urlencoded")
		{
			$parameter = $_REQUEST;
		}
		else
		{
			switch ($_SERVER['REQUEST_METHOD']) {
				case 'GET':
					$parameter = $_REQUEST;
					break;
				case 'POST':
				case 'PUT':
				case 'DELETE':
				default:
					$parameter = json_decode(file_get_contents('php://input'));
					break;
			}
		}
		foreach($parameter as $key => $req)
		{
			$this->request_param[$key] = $req;
		}
	}

	public function parameter(string $key)
	{
		return $this->request_param[$key];
	}

	public function notExist(string $key):bool
	{
		return array_key_exists($key,$this->request_param) ? false : true;
	}
}
