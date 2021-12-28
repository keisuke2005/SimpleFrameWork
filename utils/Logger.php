<?php
namespace FW\Library;

/**
* Logger Class
*
* ログ出力管理のユーティリティ
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
class Logger
{
	/**
	 * @access private
	 * @var string 出力先のログパス
	 */
	private string $log_path;

	/**
	* コンストラクタ
	*
	* ユーザ定義Configクラスに定義したパスとアプリ基準パスを連結し、
	* 出力先ログパスをメンバ変数に格納
	*
	* @access private
	* @param string $aplpath
	* @return void
	*/
	public function __construct(string $aplpath)
	{
		$this->log_path = $aplpath;
	}

	/**
	* 書き込み
	*
	* 日付、ログレベル、メッセージ、出力クラス、出力メソッドをいい感じに出力する。
	*
	* 使用者は意識しないで良い関数。
	* @access private
	* @param string $level
	* @param string $message
	* @param string $class
	* @param string $function
	* @return void
	*/
	private function write(
		string $level,
		string $message,
		string $class,
		string $function
	):void
	{
		$now  = date('Y/m/d H:i:s');
		$now .= sprintf('.%03d', substr(explode(".", (string)microtime(true))[1], 0, 3));

		error_log(
			"[{$now}][{$level}][cls:{$class}][fnc:{$function}]:{$message}\n",
			3,
			$this->log_path
		);

	}

	/**
	* ログ出力実行ログレベルFATAL用
	*
	* [YYYY/MM/DD hh:mm:ss.xxx][FATAL][cls:ClassName][fnc:FuncName]:Message
	* @access public
	* @param string $message
	* @return void
	*/
	public function fatal(string $message):void
	{
		$dbg = debug_backtrace();
		$this->write(
			'FATAL',
			$message,
			$dbg[1]['class'],
			$dbg[1]['function']
		);
	}

	/**
	* ログ出力実行ログレベルERROR用
	*
	* [YYYY/MM/DD hh:mm:ss.xxx][ERROR][cls:ClassName][fnc:FuncName]:Message
	* @access public
	* @param string $message
	* @return void
	*/
	public function error(string $message):void
	{
		$dbg = debug_backtrace();
		$this->write(
			'ERROR',
			$message,
			$dbg[1]['class'],
			$dbg[1]['function']
		);
	}

	/**
	* ログ出力実行ログレベルWARNING用
	*
	* [YYYY/MM/DD hh:mm:ss.xxx][WARNING][cls:ClassName][fnc:FuncName]:Message
	* @access public
	* @param string $message
	* @return void
	*/
	public function warning(string $message):void
	{
		$dbg = debug_backtrace();
		$this->write(
			'WARNING',
			$message,
			$dbg[1]['class'],
			$dbg[1]['function']
		);

	}

	/**
	* ログ出力実行ログレベルINFO用
	*
	* [YYYY/MM/DD hh:mm:ss.xxx][INFO][cls:ClassName][fnc:FuncName]:Message
	* @access public
	* @param string $message
	* @return void
	*/
	public function info(string $message):void
	{
		$dbg = debug_backtrace();
		$this->write(
			'INFO',
			$message,
			$dbg[1]['class'],
			$dbg[1]['function']
		);
	}

	/**
	* ログ出力実行ログレベルDEBUG用
	*
	* [YYYY/MM/DD hh:mm:ss.xxx][DEBUG][cls:ClassName][fnc:FuncName]:Message
	* @access public
	* @param string $message
	* @return void
	*/
	public function debug(string $message):void
	{
		$dbg = debug_backtrace();
		$this->write(
			'DEBUG',
			$message,
			$dbg[1]['class'],
			$dbg[1]['function']
		);
	}
}
