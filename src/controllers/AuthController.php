<?php

namespace controllers;

use app\UserAuth;
use models\User;
use utils\Url;

class AuthController extends BaseController
{
	public ?User $user;

	public function login(): void
	{
		if (UserAuth::isUserAuthenticated()) 
		{
			$_SESSION['flash']['message'] = 'Authenticated';
			$this->redirect(null);
		}

		if (!empty($_POST)) 
		{
			$login = $_POST['login'];
			$password = $_POST['password'];

			$user = User::getByLogin($login, null);

			if (!empty($user) && password_verify($password, $user->getPasswordHash()) === true)
			{
				UserAuth::setAuthenticatedUser($user);
				$this->user = $user;

				$this->redirect(null);
			}
			else
			{
				$_SESSION['flash']['message'] = 'Login or password is incorrect';
				$this->refresh();
			}
		}

		$data = [
			'message' => $_SESSION['flash']['message'] ?? null,
			'form_action' => Url::getCurrentPath(),
			'basepath' => Url::getBasePath(),
		];

		$this->view('login', $data);
	}


	public function logout(): void
	{
		if (UserAuth::isUserAuthenticated()) 
		{
			UserAuth::clearAuthenticatedUser();
			$_SESSION['flash']['message'] = 'Signed out';
			$this->user = null;
		}

		$this->redirect(null);
	}

}