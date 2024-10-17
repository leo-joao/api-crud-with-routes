<?php

namespace App\Helpers;

class PasswordHasher
{
  /**
   * Gera um hash seguro para uma senha, utilizando um login como base para criar o salt.
   *
   * Aplica o algoritmo BCRYPT para criar o hash da senha concatenada com o salt gerado a partir do login.
   *
   * @param string $login O login do usuário, utilizado para gerar o salt.
   * @param string $password A senha a ser hashada.
   * @return string O hash seguro da senha.
   */
  public static function hashPassword(string $login, string $password)
  {
    $salt = self::generateSalt($login);
    $hashedPassword = password_hash($salt . $password, PASSWORD_BCRYPT);

    return $hashedPassword;
  }

  /**
   * Verifica se a senha fornecida corresponde ao hash armazenado, utilizando o login para recriar o salt.
   *
   * @param string $login O login do usuário, utilizado para gerar o salt.
   * @param string $password A senha fornecida pelo usuário para verificação.
   * @param string $hashedPassword O hash da senha armazenada que será verificado.
   * @return bool Retorna true se a senha for válida, caso contrário, retorna false.
   */
  public static function verifyPassword(string $login, string $password, string $hashedPassword): bool
  {
    $salt = self::generateSalt($login);

    return password_verify($salt . $password, $hashedPassword);
  }

  /**
   * Gera um salt baseado no login do usuário.
   *
   * O salt é criado ao reverter a string do login, gerando uma forma simples de personalizar o salt para cada usuário.
   *
   * @param string $login O login do usuário utilizado para gerar o salt.
   * @return string O salt gerado a partir do login.
   */
  private static function generateSalt(string $login): string
  {
    return strrev($login);
  }
}
