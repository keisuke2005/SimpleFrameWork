<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

class MissingFunctionException extends UserDefinedException {
  public function __construct($subMessage){
    $this->code = 50001;
    $this->message = '実行ファンクション不在例外';
    $this->subMessage = $subMessage;
  }
}
