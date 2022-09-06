<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

class MissingControllerException extends UserDefinedException {
  public function __construct($subMessage){
    $this->code = 20001;
    $this->message = 'Controller不在例外';
    $this->subMessage = $subMessage;
  }
}
