<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Helpers\PasswordHasher;

class AuthController extends BaseController
{

  protected UserModel $userModel;

  /**
   * Autentica o usuário com base no login e senha fornecidos.
   *
   * Verifica se o usuário já está autenticado, caso contrário, valida as credenciais enviadas via POST.
   * Se as credenciais estiverem corretas, um token de autenticação é gerado e salvo como um cookie.
   *
   * @return void
   */
  public function login(): void
  {
    if ($this->checkAuth()) {
      echo self::response(true, self::HTTP_OK);
    }

    $data = self::inputPost();
    $userModel = new UserModel();

    if (empty($data['login']) || empty($data['pass'])) {
      echo self::response('Wrong credentials', self::HTTP_OK);
    }

    $login = $data['login'];
    $pass = $data['pass'];

    $user = $userModel->getUser($login);

    if (empty($user)) {
      echo self::response('Wrong credentials', self::HTTP_OK);
    }

    $userPassword = $user[0]->getPass();

    if (!PasswordHasher::verifyPassword($login, $pass, $userPassword)) {
      echo self::response('Wrong credentials', self::HTTP_OK);
    } else {
      $secretKey = self::TOKEN_KEY;
      $token = base64_encode($login . '|' . time());
      $signature = hash_hmac('sha256', $token, $secretKey);
      $token = $token . '¨' . $signature;

      setcookie('auth_token', $token, time() + 86400, "/", "", false, true);

      echo self::response(true, self::HTTP_OK);
    }
  }

  /**
   * Realiza o logoff do usuário.
   *
   * Remove o cookie de autenticação 'auth_token', efetivamente deslogando o usuário.
   *
   * @return void
   */
  public function logoff(): void
  {
    setcookie('auth_token', '', time() - 3600, "/");
  }
}
