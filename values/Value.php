<?php
namespace FW\Foundation;

/**
* 基底データオブジェクト
*
* 基底のデータオブジェクトクラス
* @access public
* @author keisuke <ukei2021@gmail.com>
* @copyright MezzoDay Corporation All Rights Reserved
* @version 1.0
* @abstract
* @package FW\Foundation
*/
abstract class Value
{
	/**
	* @access private
	* @var array 動的に値を設定し、__callで呼び出す為の配列
	*/
	private $prop = [];

	/**
	* 動的getter,Setter
	*
	* __callで動的にファンクションを定義
	* @access public
	* @param $method 呼び出しファンクション名
	* @param $args 引数
	* @return mixed
	*/
	public function __call($method, $args)
	{
		if(strpos($method,'set_') === 0)
		{
			$prop_name = str_replace('set_', '', $method);
			if(property_exists(get_class($this),$prop_name))
			{
				$this->$prop_name = $args[0];
				return true;
			}
			$this->prop[$prop_name] = $args[0];
			return true;
		}
		if(strpos($method,'get_') === 0)
		{
			$prop_name = str_replace('get_', '', $method);
			if(property_exists($this,$prop_name)) return $this->$prop_name;
			if(isset($this->prop[$prop_name])) return $this->prop[$prop_name];
			return false;
		}

	}
	/**
	* prop変数一括取り出し
	* @access public
	* @return array
	*/
	public function get_prop()
	{
		return $this->prop;
	}
}
