<?php

namespace App\Models;

use PDO;
use PDOException;


class SaleItemsModel extends BaseModel
{

  protected $table = 'sale_items';

  public function getItems(int $saleId): array
  {
    return $this->get("WHERE sale_id = $saleId");
  }

  public function updateItem($id, $data): bool
  {
    return $this->update('id', $id, $data);
  }
  public function deleteItem($id): bool
  {
    return $this->delete('id', $id);
  }

  /**
   * Insere um novo tipo de produto na tabela.
   *
   * Recebe um array de dados e insere um novo registro na tabela de tipos de produtos.
   *
   * @param array $data Dados do tipo de produto a ser inserido.
   * @return int O ID do novo tipo de produto inserido.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function createItem($data): int
  {
    return $this->insert($data);
  }
}
