<?php
namespace FW\Exception;

require_once(__DIR__."/FwException.php");

class UserDefinedException extends FwException {
  public function __construct($subMessage){
    $this->code = 2001;
    $this->message = 'ユーザ定義例外';
    $this->subMessage = $subMessage;
  }
  public function output():void
  {
    /*
    echo "<pre>";
    var_dump($this->getTrace());
    echo "</pre>";
    exit;
    */
    $arrow = "▲";
    $html = "";
    $html .=<<<__EHEAD
<html lang="ja">
  <head>
    <meta charaset="UTF-8"><title>{$this->getMessage()}</title>
    <style>
    .e-header{padding:10px;background: #79B4B7;width: 100%;}
    body{margin:0px;background: #FEFBF3;}
    .e-content{padding:10px;}
    table{width: 100%;border-collapse: collapse;border-spacing: 0;}
    table th,table td{padding: 10px 0;text-align: left;}
    table tr:nth-child(odd){background-color: #eee}
    </style>
  </head>
  <body>
    <header class="e-header">
      <h1>エラーコード {$this->getCode()} {$this->getMessage()}</h1>
    </header>
    <div class="e-content">
      <h3>{$this->getSubMessage()}</h3><br>
      <h2>スタックトレース</h2><br>
      <table>
__EHEAD;

    $flg = true;
    foreach($this->getTrace() as $trace)
    {
      $html .= $flg ? "" : "<tr><td>{$arrow}</td><td>{$arrow}</td><td>{$arrow}</td></tr>";
      if($flg) $flg = false;
      $class = isset($trace['class']) ? $trace['class'] : "";
      $type = isset($trace['type']) ? $trace['type'] : "";
      $html .= "<tr><td>{$class}{$type}{$trace['function']}</td><td>{$trace['file']}</td><td>{$trace['line']}行目</td></tr>";
    }
    $html .=<<<__EFOOT
      </table>
    </div>
  </body>
</html>
__EFOOT;
    echo $html;
  }
}
