<?php

namespace App\Models;

use PDO;
use PDOException;

class ProductTypeModel extends BaseModel
{

  /**
   * Nome da tabela que armazena os tipos de tipos de produtos.
   *
   * @var string
   */
  protected $table = 'product_type';

  /**
   * Retorna todos os tipos de produtos da tabela.
   *
   * @return array Lista de tipos de produtos.
   */
  public function getTypes(): array
  {
    return $this->get();
  }

  /**
   * Retorna os detalhes de um tipo de produto específico.
   *
   * Executa uma consulta SQL para buscar o tipo de produto baseado no seu ID.
   *
   * @param int $id O ID do tipo de produto a ser buscado.
   * @return array Dados do tipo de produto.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function getType(int $id): array
  {
    $sql = "SELECT * FROM {$this->table} WHERE id = :id";
    $params = ['id' => $id];
    return $this->executeQuery($sql, $params);
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
  public function insertType($data): int
  {
    return $this->insert($data);
  }

  /**
   * Atualiza as informações de um tipo de produto existente.
   *
   * Recebe o ID do tipo de produto e um array de dados, e atualiza o registro correspondente no banco de dados.
   *
   * @param int $id O ID do tipo de produto a ser atualizado.
   * @param array $data Dados atualizados do tipo de produto.
   * @return bool Retorna true em caso de sucesso ou false em caso de falha.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function updateType($id, $data): bool
  {
    return $this->update('id', $id, $data);
  }

  /**
   * Deleta um tipo de produto da tabela.
   *
   * Recebe o ID de um tipo de produto e remove-o do banco de dados.
   *
   * @param int $id O ID do tipo de produto a ser deletado.
   * @return bool Retorna true em caso de sucesso ou false em caso de falha.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function deleteType($id): bool
  {
    return $this->delete('id', $id);
  }
}
