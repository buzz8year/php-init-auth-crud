<?php

namespace controllers;

use interfaces\ControllerInterface;
use utils\Url;
use app\View;


class BaseController implements ControllerInterface
{
	public function refresh(): never
	{
		header(sprintf('Location: %s', Url::getCurrentPath()));
		die;
	}

	public function redirect(string | null $query): never
	{
		header(sprintf('Location: %s%s', Url::getBasePath(), $query));
		die;
	}

	public function view(string $template, array $data): void
	{
		$view = new View($template);
		$view->render($data);

		unset($_SESSION['flash']);
	}
}