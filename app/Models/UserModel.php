<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Classe responsável pelas operações relacionadas ao modelo de Usuário.
 *
 * Esta classe interage com a tabela 'users' no banco de dados e fornece métodos
 * para buscar usuários e senhas.
 */

class UserModel extends BaseModel
{

  protected $table = 'users';

  protected $id;
  protected $login;
  protected $pass;

  /**
   * Busca um usuário no banco de dados pelo login (email).
   *
   * @param string $login O login (email) do usuário.
   * @return array Retorna um array com os dados do usuário.
   */

  public function getUser(string $login): array
  {
    return $this->get("WHERE email = '$login'");
  }

  /**
   * Retorna a senha do usuário.
   *
   * @return string A senha armazenada no objeto do usuário.
   */

  public function getPass()
  {
    return $this->pass;
  }
}
