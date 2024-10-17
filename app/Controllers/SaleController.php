<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductTypeModel;
use App\Models\SaleItemsModel;
use App\Models\SaleModel;

class SaleController extends BaseController
{
  protected ProductModel $productModel;
  protected ProductTypeModel $productTypeModel;
  protected SaleItemsModel $saleItemsModel;
  protected SaleModel $saleModel;
  private array $data;

  public function __construct()
  {
    if (!$this->checkAuth()) {
      echo self::response('Invalid token', self::HTTP_UNAUTHORIZED);
      die();
    }

    $this->saleModel = new SaleModel();
    $this->productModel = new ProductModel();
    $this->productTypeModel = new ProductTypeModel();
    $this->saleItemsModel = new SaleItemsModel();
    $this->data['sales'] = $this->saleModel->getSales();
  }

  /**
   * Exibe uma lista de todas as vendas registradas.
   *
   * Retorna um array contendo as vendas atuais.
   *
   * @return void
   */
  public function index(): void
  {
    echo self::response($this->data, self::HTTP_OK);
  }

  /**
   * Exibe uma venda específica com base no ID fornecido.
   *
   * @param mixed $request Informações da requisição.
   * @param int $id O ID da venda a ser exibida.
   * @return void
   */
  public function show($request, int $id): void
  {
    $sale = $this->saleModel->getSale($id);

    if (!$sale) {
      echo self::response('Sale not found', self::HTTP_NOT_FOUND);
      die();
    }

    echo self::response($sale, self::HTTP_OK);
  }

  /**
   * Registra uma nova venda e seus itens no sistema.
   *
   * Recebe os dados da venda e os itens da requisição POST, incluindo produtos, quantidades,
   * e realiza o cálculo de impostos e totais. Insere a venda e os itens correspondentes no banco de dados.
   *
   * Valida se os campos de produtos e quantidades estão presentes.
   *
   * @return void
   */
  public function store(): void
  {
    $validation = [];
    $data = self::inputPost();

    if (empty($data['products']))
      $validation[] = 'Products are required';
    if (empty($data['quantities']))
      $validation[] = 'Quantities are required';

    if (count($validation) > 0) {
      $this->response($validation, self::HTTP_BAD_REQUEST);
      return;
    }

    $currentDateTime = date("Y-m-d H:i:s");

    $saleDate = [
      'sale_date' => $currentDateTime
    ];

    $saleId = $this->saleModel->insert($saleDate);

    if (!$saleId) {
      echo self::response('Erron on registering sale', self::HTTP_BAD_REQUEST);
    }

    $totalTaxes = 0;
    $totalPrices = 0;

    for ($i = 0; $i < count($data["products"]); $i++) {

      $productId = $data["products"][$i];
      $productQuantity = $data["quantities"][$i];

      $productInfo = $this->productModel->getProduct($productId);
      $productType = $productInfo[0]->type;
      $productPrice = $productInfo[0]->price;

      $typeInfo = $this->productTypeModel->getType($productType);
      $taxPercentage = $typeInfo[0]->tax_percentage;

      $taxValue = floatval(($productPrice * ($taxPercentage / 100)) * $productQuantity);
      $itemTotal = floatval($productPrice * $productQuantity);

      $totalTaxes += floatval($taxValue);
      $totalPrices += floatval($itemTotal);

      $item = [
        "product_id" => $productId,
        "sale_id" => $saleId,
        "quantity" => $productQuantity,
        "taxes" => $taxValue,
        "total_price" => $itemTotal,
      ];
      $this->saleItemsModel->createItem($item);
    }

    $this->saleModel->updateSale($saleId, ['taxes' => $totalTaxes, 'total' => $totalPrices]);

    echo self::response('Sale succesfully registered', self::HTTP_CREATED);
  }

  /**
   * Atualiza uma venda existente no sistema.
   *
   * Esta função ainda não foi implementada.
   *
   * @return void
   */
  public function update(): void
  {
    // Implementação pendente
  }

  /**
   * Remove uma venda específica com base no ID fornecido.
   *
   * Remove a venda e todos os itens relacionados ao ID fornecido.
   *
   * @param mixed $request Informações da requisição.
   * @param int $id O ID da venda a ser removida.
   * @return void
   */
  public function destroy($request, int $id): void
  {
    $sale = $this->saleModel->getSale($id);

    if (!$sale) {
      echo self::response('Sale not found', self::HTTP_NOT_FOUND);
      die();
    }

    $saleItems = $this->saleItemsModel->getItems($id);

    foreach ($saleItems as $item) {
      $this->saleItemsModel->deleteItem($item->$id);
    }

    $this->saleModel->deleteSale($id);

    echo self::response($this->saleModel->deleteSale($id), self::HTTP_OK);
  }
}
