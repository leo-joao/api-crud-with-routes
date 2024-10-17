<?php

namespace App\Models;

use PDO;
use PDOException;

class ProductModel extends BaseModel
{

  /**
   * Nome da tabela que armazena os produtos.
   *
   * @var string
   */
  protected $table = 'products';

  /**
   * Retorna todos os produtos da tabela.
   *
   * @return array Lista de produtos.
   */
  public function getProducts(): array
  {
    return $this->get();
  }

  /**
   * Retorna os detalhes de um produto específico.
   *
   * Executa uma consulta SQL para buscar o produto baseado no seu ID.
   *
   * @param int $id O ID do produto a ser buscado.
   * @return array Dados do produto.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function getProduct(int $id): array
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";
    $params = ['id' => $id];
    return $this->executeQuery($sql, $params);
  }

  /**
   * Insere um novo produto na tabela.
   *
   * Recebe um array de dados e insere um novo registro na tabela de produtos.
   *
   * @param array $data Dados do produto a ser inserido.
   * @return int O ID do novo produto inserido.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function insertProduct($data): int
  {
    return $this->insert($data);
  }

  /**
   * Atualiza as informações de um produto existente.
   *
   * Recebe o ID do produto e um array de dados, e atualiza o registro correspondente no banco de dados.
   *
   * @param int $id O ID do produto a ser atualizado.
   * @param array $data Dados atualizados do produto.
   * @return bool Retorna true em caso de sucesso ou false em caso de falha.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function updateProduct($id, $data): bool
  {
    return $this->update('id', $id, $data);
  }

  /**
   * Deleta um produto da tabela.
   *
   * Recebe o ID de um produto e remove-o do banco de dados.
   *
   * @param int $id O ID do produto a ser deletado.
   * @return bool Retorna true em caso de sucesso ou false em caso de falha.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function deleteProduct($id): bool
  {
    return $this->delete('id', $id);
  }
}
