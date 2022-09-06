<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

class MissingViewException extends UserDefinedException {
  public function __construct($subMessage){
    $this->code = 40001;
    $this->message = 'View不在例外';
    $this->subMessage = $subMessage;
  }
}
