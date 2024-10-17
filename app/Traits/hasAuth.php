<?php

namespace App\Traits;

/**
 * Verifica se o usuário está autenticado com base no cookie de autenticação.
 *
 * O cookie 'auth_token' é verificado e, se válido, a autenticação é considerada bem-sucedida.
 * Se o token for inválido ou expirar, o cookie é removido.
 *
 * @return bool Retorna true se o usuário estiver autenticado, caso contrário, retorna false.
 */
trait hasAuth
{

  /**
   * Chave de autenticação de token.
   *
   * @var string
   */
  public const TOKEN_KEY = 'KeyToken*963,789+';

  public function checkAuth(): bool
  {
    if (!isset($_COOKIE['auth_token'])) {
      return false;
    }

    $secretKey = self::TOKEN_KEY;
    $cookieValue = $_COOKIE['auth_token'];

    list($token, $signature) = explode('¨', $cookieValue);

    $expectedSignature = hash_hmac('sha256', $token, $secretKey);

    if (!hash_equals($expectedSignature, $signature)) {
      setcookie('auth_token', '', time() - 3600, "/");
      return false;
    }

    $decodedToken = base64_decode($token);
    list($login, $timestamp) = explode('|', $decodedToken);

    if (time() - $timestamp > 86400) {
      setcookie('auth_token', '', time() - 3600, "/");
      return false;
    }

    $userModel = new \App\Models\UserModel();
    $user = $userModel->getUser($login);

    return !empty($user);
  }
}
