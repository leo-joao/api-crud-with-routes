<?php

namespace App\Routes;

use App\Helpers\Request;
use App\Helpers\Uri;
use Exception;

class Router
{
  /**
   * Namespace padrão dos controllers.
   *
   * @var string
   */
  const CONTROLLER_NAMESPACE = 'App\\Controllers';

  /**
   * Carrega e executa um método de um controller com os parâmetros fornecidos.
   *
   * @param string $controller O nome do controller a ser carregado.
   * @param string $method O nome do método a ser chamado no controller.
   * @param array $params Parâmetros opcionais a serem passados para o método.
   * @throws Exception Caso o controller ou o método não existam.
   * @return void
   */
  public static function load(string $controller, string $method, array $params = []): void
  {
    try {
      $controllerNamespace = self::CONTROLLER_NAMESPACE . '\\' . $controller;

      if (!class_exists($controllerNamespace)) {
        throw new Exception("Controller {$controller} does not exist.");
      }

      $controllerInstance = new $controllerNamespace;

      if (!method_exists($controllerInstance, $method)) {
        throw new Exception("Method {$method} does not exist in Controller {$controller}.");
      }

      $controllerInstance->$method((object) $_REQUEST, ...$params);
    } catch (\Throwable $th) {
      echo $th->getMessage();
    }
  }

  /**
   * Define as rotas da aplicação, categorizadas por métodos HTTP (GET, POST, PUT, DELETE).
   *
   * @return array Um array contendo as rotas mapeadas para suas respectivas ações.
   */
  public static function routes(): array
  {
    return [
      'get' => [
        '/' => fn() => self::load('AuthController', 'login'),
        '/sales' => fn() => self::load('SaleController', 'index'),
        '/sales/{id}' => fn($id) => self::load('SaleController', 'show', [$id]),
        '/products' => fn() => self::load('ProductController', 'index'),
        '/products/{id}' => fn($id) => self::load('ProductController', 'show', [$id]),
        '/product-types' => fn() => self::load('ProductTypeController', 'index'),
        '/product-types/{id}' => fn($id) => self::load('ProductTypeController', 'show', [$id]),
      ],
      'post' => [
        '/login' => fn() => self::load('AuthController', 'login'),
        '/logoff' => fn() => self::load('AuthController', 'logoff'),
        '/sales/create' => fn() => self::load('SaleController', 'store'),
        '/products/create' => fn() => self::load('ProductController', 'store'),
        '/product-types/create' => fn() => self::load('ProductTypeController', 'store'),
      ],
      'put' => [
        '/sales/{id}/update' => fn($id) => self::load('SaleController', 'update', [$id]),
        '/products/{id}/update' => fn($id) => self::load('ProductController', 'update', [$id]),
        '/product-types/{id}/update' => fn($id) => self::load('ProductTypeController', 'update', [$id]),
      ],
      'delete' => [
        '/sales/{id}/delete' => fn($id) => self::load('SaleController', 'destroy', [$id]),
        '/products/{id}/delete' => fn($id) => self::load('ProductController', 'destroy', [$id]),
        '/product-types/{id}/delete' => fn($id) => self::load('ProductTypeController', 'destroy', [$id]),
      ],
    ];
  }

  /**
   * Executa o roteamento da aplicação.
   *
   * Esta função identifica a rota atual com base na URI e no método HTTP e então executa a ação correspondente.
   *
   * @throws Exception Caso a rota ou a ação correspondente não existam.
   * @return void
   */
  public static function execute(): void
  {
    try {
      $routes = self::routes();
      $request = Request::get();
      $uri = Uri::get('path');

      if (!isset($routes[$request])) {
        throw new Exception('Route does not exist');
      }

      $matchedRoute = null;
      $params = [];

      foreach ($routes[$request] as $route => $action) {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
          array_shift($matches);
          $params = $matches;
          $matchedRoute = $action;
          break;
        }
      }

      if (!$matchedRoute) {
        throw new Exception('Route does not exist');
      }

      if (!is_callable($matchedRoute)) {
        throw new Exception("Route {$uri} is not callable");
      }

      $matchedRoute(...$params);
    } catch (\Throwable $th) {
      echo $th->getMessage();
    }
  }
}
