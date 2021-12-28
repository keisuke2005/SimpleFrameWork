<?php
namespace FW\Foundation;

require_once(__DIR__."/../views/View.php");

class JsonView extends View {
	public function json(JsonValue $value)
	{
		$this->logger()->info("Start Function");
		echo $value->getJson();
		$this->logger()->info("End Function");
		return $value;
	}
}
