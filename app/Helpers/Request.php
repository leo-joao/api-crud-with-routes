<?php

namespace App\Helpers;

class Request
{

  /**
   * Obtém o método HTTP da requisição atual.
   *
   * Retorna o método HTTP (GET, POST, etc.) utilizado na requisição atual, convertido para letras minúsculas.
   *
   * @return string O método HTTP da requisição em letras minúsculas.
   */

  public static function get(): string
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }
}
