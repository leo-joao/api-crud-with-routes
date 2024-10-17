<?php

namespace App\Controllers;

use App\Traits\hasAuth;

abstract class BaseController
{

  use hasAuth;
  /**
   * Código de status HTTP 200 OK.
   *
   * @var int
   */
  public const HTTP_OK = 200;

  /**
   * Código de status HTTP 201 Created.
   *
   * @var int
   */
  public const HTTP_CREATED = 201;

  /**
   * Código de status HTTP 204 No Content.
   *
   * @var int
   */
  public const HTTP_NO_CONTENT = 204;

  /**
   * Código de status HTTP 400 Bad Request.
   *
   * @var int
   */
  public const HTTP_BAD_REQUEST = 400;

  /**
   * Código de status HTTP 401 Unauthorized.
   *
   * @var int
   */
  public const HTTP_UNAUTHORIZED = 401;

  /**
   * Código de status HTTP 404 Not Found.
   *
   * @var int
   */
  public const HTTP_NOT_FOUND = 404;

  /**
   * Código de status HTTP 500 Internal Server Error.
   *
   * @var int
   */
  public const HTTP_INTERNAL_SERVER_ERROR = 500;

  /**
   * Array de resposta padronizada.
   *
   * @var array
   */
  protected array $response;

  /**
   * Captura e filtra dados de uma requisição POST.
   *
   * Este método verifica se o conteúdo da requisição é do tipo JSON ou POST padrão, e então aplica o filtro desejado aos dados.
   *
   * @param int $filter O filtro a ser aplicado aos dados (por padrão, FILTER_DEFAULT).
   * @return array Um array contendo os dados filtrados da requisição.
   */
  public function inputPost(int $filter = FILTER_DEFAULT): array
  {
    $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

    if (strpos($contentType, 'application/json') !== false) {
      $data = json_decode(file_get_contents('php://input'), true);
    } else {
      $data = $_POST;
    }

    if (!$data)
      return [];

    return filter_var_array($data, $filter) ?? [];
  }

  /**
   * Envia uma resposta em formato JSON com o código de status HTTP fornecido.
   *
   * @param mixed $data Os dados a serem enviados na resposta (podem ser de qualquer tipo).
   * @param int $code O código de status HTTP para a resposta (por padrão, HTTP_OK).
   * @return void
   */
  public function response(mixed $data, int $code = self::HTTP_OK): void
  {
    http_response_code($code);
    header("Content-Type: application/json");

    echo json_encode([
      "data" => $data,
      "code" => $code
    ]);

    exit;
  }
}
