<?php
namespace FW\Exception;

require_once(__DIR__."/UserDefinedException.php");

class MissingRouteDefinitionFileException extends UserDefinedException {
  public function __construct($subMessage){
    $this->code = 10002;
    $this->message = 'ルート定義ファイル不在例外';
    $this->subMessage = $subMessage;
  }
}
