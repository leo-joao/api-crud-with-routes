<?php

namespace App\Helpers;

class Uri
{
  /**
   * Retorna uma parte específica da URL atual com base no tipo fornecido.
   *
   * @param string $type O tipo de componente da URL a ser retornado (ex: 'path', 'host', etc.).
   * @return string A parte da URL especificada pelo parâmetro $type.
   */
  public static function get($type): string
  {
    $uri = $_SERVER['REQUEST_URI'];

    if ($uri !== "/") {
      $uri = rtrim($uri, '/');
    }

    return parse_url($uri)[$type];
  }
}
