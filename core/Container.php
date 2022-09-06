<?php
namespace FW\Foundation;

use FW\Library\Logger;

class Container
{
  /**
  * インスタンス生成用Closure群
  * @access private
  * @var Closure[]
  */
  private array $container = [];

  const DEFAULT_LOG_PATH = "/logs/application.log";

  /**
  * コンストラクタ
  *
  * クラス生成ロジックを設定していく。
  *
  * キー名はクラス名のクロージャ型の配列。
  * @access public
  * @return void
  */
  function __construct()
  {
    $this->container['Logger'] = fn($path) => new Logger($path);
    $this->container['Request'] = fn() => Request::get();
    $this->container['MvcInfo'] = fn($path) => new MvcInfo($this->get('Request')(),$path);
    $this->container['ApplicationRouter'] = fn($path) => new \ApplicationRouter(
      $this->get('Logger')($path.Self::DEFAULT_LOG_PATH),
      $this->get('Request')(),
      $this->get('MvcInfo')($path)
    );
  }
  /**
  * クロージャ取得
  *
  * インスタンス取得に使うクロージャを取得
  * @access public
  * @param string キー名（クラス名）
  * @return Closure
  */
  public function get(string $classname): \Closure
  {
    return $this->container[$classname];
  }
}
