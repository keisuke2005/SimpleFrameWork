<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

class RouteDefinitionException extends UserDefinedException {
  public function __construct($subMessage){
    $this->code = 10001;
    $this->message = 'ルート定義例外';
    $this->subMessage = $subMessage;
  }
}
