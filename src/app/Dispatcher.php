<?php

namespace app;

use utils\Url;

class Dispatcher
{
	protected const string DEFAULT_METHODNAME = 'index';
	protected string $className;
	protected string $methodName;

	public function __construct(array $data)
	{
		$this->className = ucfirst($data[0] ?? DEFAULT_CONTROLLER_NAME);
		$this->methodName = strval($data[1] ?? self::DEFAULT_METHODNAME);
	}

	public function dispatch()
	{
		if (empty($this->className)) 
		{
			header(sprintf('Location: %s', Url::getCurrentPath()));
			die;
		}
		
		$className = sprintf('\controllers\\%sController', $this->className);
		$objController = new $className();

		if (method_exists($objController, $this->methodName))
		{
			$objController->{$this->methodName}();
		}
		else 
		{
			header('HTTP/1.0 404');
			die;
		}
	}
}