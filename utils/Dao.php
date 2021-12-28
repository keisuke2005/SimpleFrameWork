<?php
namespace FW\Library;

/**
* データベースアクセスオブジェクト
*
* データベースオブジェクト管理及びSQL発行
*
* 本フレームワーク方針として、生のSQLを書き、不必要にラップしない
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @package FW\Library
*/
class Dao {
	/**
	* @access private
	* @var Dao 自身を管理するDaoオブジェクト格納変数
	* @see Dao
	*/
	private static $dao;

	/**
	* @access private
	* @var PDO PDOオブジェクト格納変数
	*/
	private static \PDO $pdo;

	/**
	* コンストラクタ
	*
	* ユーザ定義Configクラスに定義したDB種別、DBホスト、DBポート、DB名を使用して、
	* PDOインスタンスを生成し、メンバ変数に格納する。
	*
	* インスタンスの乱立を防ぐ為、privateとする
	* @access private
	* @return void
	*/
	private function __construct()
	{
		$dsn = sprintf(
			'%s:host=%s:%s;dbname=%s;charset=utf8mb4',
			\Config::DB_TYPE,
			\Config::DB_HOST,
			\Config::DB_PORT,
			\Config::DB_NAME
		);
		try
		{
			self::$pdo = new \PDO($dsn, \Config::DB_USER, \Config::DB_PW);
		}
		catch(\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* インスタンス取得
	*
	* 外部からこのクラスを扱うときは、こちらを利用してインスタンスを取得する。
	* @access public
	* @return Dao
	*/
	public static function db(): Dao
	{
		try
		{
			if (!isset(self::$dao))
			{
				self::$dao = new Dao();
			}
			return self::$dao;
		}
		catch(\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* プリペア処理
	*
	* PDO::prepare関数実行したものを返却
	* @access private
	* @param string $sql
	*/
	private function prepare(string $sql)
	{
		try
		{
			return self::$pdo->prepare($sql);
		}
		catch(\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* バインド
	*
	* 与えられたバインドパラメータをbindParam関数の引数として実行する。
	* @access private
	* @param $stmt
	* @param array $params
	*/
	private function bind($stmt,?array $params)
	{
		if(isset($params))
		{
			for($i = 0; $i < count($params); $i++)
			{
				$stmt->bindParam($i+1,$params[$i]);
			}
		}
		return $stmt;
	}

	/**
	* レコード件数取得
	*
	* sqlの取得結果のレコード数を返却する。
	*
	* 対応CLUD
	** SELECT
	* @access public
	* @param string $sql
	* @param array $params
	* @return int
	*/
	public function count_row(string $sql,?array $params = null): int
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$stmt->execute();
			return $stmt->rowCount();
		}
		catch(\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* レコード取得1件引き用
	*
	* SELECTで1件引きの為のメソッド。2件以上取得できるときも1件になる。
	*
	* 対応CLUD
	** SELECT
	* @access public
	* @param string $sql
	* @param array $params
	* @return array
	*/
	public function show_one_row(string $sql,?array $params = null):array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$stmt->execute();
			if($stmt->rowCount() < 1)
			{
				return array("result" => false);
			}
			$result =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
			return array(
				"result" => true,
				"data" => $result[0]
			);
		}
		catch(\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* レコード取得複数行用
	*
	* SELECTで複数行取得の為のメソッド。
	*
	* 対応CLUD
	** SELECT
	* @access public
	* @param string $sql
	* @param array $params
	* @return array
	*/
	public function show_any_rows(string $sql,?array $params = null): array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$stmt->execute();
			if($stmt->rowCount() < 1)
			{
				return array("result" => false);
			}
			$result =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
			return array(
				"result" => true,
				"data" => $result
			);
		}
		catch (\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* レコード登録且つ登録した際のID取得メソッド
	*
	* Insert時にlastInsertIdを戻り値で返す。
	*
	* 対応CLUD
	** INSERT
	* @access public
	* @param string $sql
	* @param array $params
	* @return array
	*/
	public function add_one_row(string $sql,?array $params = null): array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$bool = $stmt->execute();
			$result = array(
				"result" => $bool,
				"id" => null
			);
			if($result)
			{
				$result["id"] = self::$pdo->lastInsertId();
			}
			return $result;
		}
		catch (\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}

	/**
	* 更新全般
	*
	* 更新系のSQLで使用可能
	*
	* 対応CLUD
	** INSERT
	** UPDATE
	** DELETE
	** CREATE
	* @access public
	* @param string $sql
	* @param array $params
	* @return array
	*/
	public function mod_exec(string $sql,?array $params = null): array
	{
		try
		{
			$stmt = $this->prepare($sql);
			$stmt = $this->bind($stmt,$params);
			$bool = $stmt->execute();
			return array(
				"result" => $bool,
				"mod_row" => $stmt->rowCount()
			);
		}
		catch (\PDOException $e)
		{
			die('DataBase Error:' .$e->getMessage());
		}
	}
}
