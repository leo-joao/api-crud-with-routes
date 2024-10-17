<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Controllers\AuthController;

class ProductController extends BaseController
{
  /**
   * Instância do ProductModel.
   *
   * @var ProductModel
   */
  protected ProductModel $productModel;

  /**
   * Armazena dados a serem retornados para as requisições.
   *
   * @var array
   */
  private array $data;

  /**
   * Construtor da classe ProductController.
   *
   * Inicializa os modelos de produtos e tipos de produtos, além de preencher a
   * variável $data com os produtos existentes.
   */
  public function __construct()
  {

    if (!$this->checkAuth()) {
      echo self::response('Invalid token', self::HTTP_UNAUTHORIZED);
      die();
    }

    $this->productModel = new ProductModel();
    $this->data['products'] = $this->productModel->getProducts();
  }

  /**
   * Retorna a lista de todos os produtos.
   *
   * Envia um JSON com a lista de produtos armazenada em $data.
   *
   * @return void
   */
  public function index(): void
  {
    echo self::response($this->data, self::HTTP_OK);
  }

  /**
   * Exibe as informações de um produto específico.
   *
   * Recebe o ID de um produto e retorna seus detalhes em JSON.
   *
   * @param mixed $request Requisição recebida (não utilizado no código atual).
   * @param int $id O ID do produto a ser exibido.
   * @return void
   */
  public function show($request, int $id): void
  {
    $product = $this->productModel->getProduct($id);

    if (!$product) {
      echo self::response('Product not found', self::HTTP_NOT_FOUND);
      die();
    }

    echo self::response($product, self::HTTP_OK);
  }

  /**
   * Armazena um novo produto no banco de dados.
   *
   * Valida os dados de entrada e, caso estejam corretos, insere o produto.
   *
   * @return void
   */
  public function store(): void
  {
    $validation = [];
    $data = self::inputPost();

    if (empty($data['type']))
      $validation[] = 'Product Type is Required';
    if (empty($data['name']))
      $validation[] = 'Product Name is Required';
    if (empty($data['price']))
      $validation[] = 'Product Price is Required';

    if (count($validation) > 0) {
      $this->response($validation, self::HTTP_BAD_REQUEST);
      return;
    }

    echo self::response($this->productModel->insertProduct($data));
  }

  /**
   * Atualiza as informações de um produto existente.
   *
   * Recebe o ID de um produto e os novos dados, e atualiza as informações no banco de dados.
   *
   * @param mixed $request Requisição recebida (não utilizado no código atual).
   * @param int $id O ID do produto a ser atualizado.
   * @return void
   */
  public function update($request, int $id): void
  {
    $validation = [];
    $data = self::inputPost();

    if (empty($data['type']))
      $validation[] = 'Product Type is Required';
    if (empty($data['name']))
      $validation[] = 'Product Name is Required';
    if (empty($data['price']))
      $validation[] = 'Product Price is Required';

    if (count($validation) > 0) {
      $this->response($validation, self::HTTP_BAD_REQUEST);
      return;
    }

    echo self::response($this->productModel->updateProduct($id, $data));
  }

  /**
   * Deleta um produto do banco de dados.
   *
   * Recebe o ID de um produto e remove-o do banco de dados, se ele existir.
   *
   * @param mixed $request Requisição recebida (não utilizado no código atual).
   * @param int $id O ID do produto a ser removido.
   * @return void
   */
  public function destroy($request, int $id): void
  {
    $product = $this->productModel->getProduct($id);

    if (!$product) {
      echo self::response('Product not found', self::HTTP_NOT_FOUND);
      die();
    }
    echo self::response($this->productModel->deleteProduct($id));
  }
}
