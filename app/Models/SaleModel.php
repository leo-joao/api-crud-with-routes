<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Classe responsável pelas operações relacionadas ao modelo de Vendas.
 *
 * Esta classe interage com a tabela 'sales' no banco de dados e fornece métodos
 * para buscar, inserir, atualizar e deletar vendas.
 */

class SaleModel extends BaseModel
{

  protected $table = 'sales';

  /**
   * Retorna todas as vendas registradas no banco de dados.
   *
   * @return array Um array contendo todas as vendas.
   */

  public function getSales(): array
  {
    return $this->get();
  }

  /**
   * Busca uma venda específica no banco de dados pelo ID.
   *
   * @param int $id O ID da venda a ser buscada.
   * @return array Um array com os dados da venda, incluindo informações dos itens e produtos associados.
   */

  public function getSale(int $id): array
  {
    $sql = <<<SQL
      SELECT
        sl.id,
        sl.sale_date,
        sl.taxes,
        sl.total,
        si.*,
        pr.name,
        pr.type,
        pt.name,
        pt.tax_percentage
      FROM {$this->table} AS sl
      INNER JOIN sale_items AS si ON si.sale_id = sl.id
      INNER JOIN products AS pr ON pr.id = si.product_id
      INNER JOIN product_type AS pt ON pt.id = pr.type
      WHERE sl.id = :id;
    SQL;
    $params = ['id' => $id];
    return $this->executeQuery($sql, $params);
  }

  /**
   * Insere uma nova venda no banco de dados.
   *
   * @param array $data Os dados da venda a serem inseridos.
   * @return int O ID da nova venda inserida.
   */

  public function insertSale($data): int
  {
    return $this->insert($data);
  }

  /**
   * Atualiza os dados de uma venda existente.
   *
   * @param int $id O ID da venda a ser atualizada.
   * @param array $data Os novos dados da venda.
   * @return bool Retorna true em caso de sucesso, ou false em caso de falha.
   */

  public function updateSale($id, $data): bool
  {
    return $this->update('id', $id, $data);
  }

  /**
   * Deleta uma venda do banco de dados pelo ID.
   *
   * @param int $id O ID da venda a ser deletada.
   * @return bool Retorna true em caso de sucesso, ou false em caso de falha.
   */

  public function deleteSale($id): bool
  {
    return $this->delete('id', $id);
  }
}
