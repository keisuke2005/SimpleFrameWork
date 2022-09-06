<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

class MissingModelException extends UserDefinedException {
  public function __construct($subMessage){
    $this->code = 30001;
    $this->message = 'Model不在例外';
    $this->subMessage = $subMessage;
  }
}
