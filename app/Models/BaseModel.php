<?php

namespace App\Models;

use App\Database\Database;
use PDO;
use PDOException;

abstract class BaseModel
{
  /**
   * @var string A tabela associada ao modelo.
   */
  protected $table;

  /**
   * Executa uma consulta SQL genérica e retorna o resultado como um array de objetos.
   *
   * @param string $sql A consulta SQL a ser executada.
   * @return array Um array de objetos da classe chamadora.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function executeQuery(string $sql, array $params = []): array
  {
    try {
      $connection = Database::getInstance();
      $stmt = $connection->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    } catch (PDOException $e) {
      throw new PDOException("Erro ao executar a consulta: " . $e->getMessage());
    }
  }

  /**
   * Retorna todos os registros da tabela com a opção de adicionar uma cláusula WHERE.
   *
   * @param string|null $where (Opcional) Uma cláusula WHERE para filtrar os resultados.
   * @return array Um array de objetos da classe chamadora contendo os registros encontrados.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function get(?string $where = ''): array
  {
    try {
      $connection = Database::getInstance();
      $sql = "SELECT * FROM {$this->table} {$where}";
      // TODO - Remover
      // echo $sql;
      return $connection->query($sql)->fetchAll(PDO::FETCH_CLASS, static::class);
    } catch (PDOException $e) {
      throw new PDOException("Error on obtaining registers: " . $e->getMessage());
    }
  }

  /**
   * Deleta um registro da tabela baseado em uma coluna e um valor específico.
   *
   * @param string $column O nome da coluna usada para identificar o registro a ser deletado.
   * @param int $id O valor da coluna (geralmente o ID) que identifica o registro a ser deletado.
   * @return bool Retorna true em caso de sucesso ou false em caso de falha.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function delete(string $column = 'id', int $id): bool
  {
    try {
      $connection = Database::getInstance();
      $sql = "DELETE FROM {$this->table} WHERE {$column} = :id";
      $prepare = $connection->prepare($sql);
      $prepare->bindValue(":id", $id, PDO::PARAM_INT);
      return $prepare->execute();
    } catch (PDOException $e) {
      throw new PDOException("Error on deleting register: " . $e->getMessage());
    }
  }

  /**
   * Atualiza um registro específico na tabela com novos dados.
   *
   * @param string $column O nome da coluna usada para identificar o registro a ser atualizado.
   * @param int $id O valor da coluna (geralmente o ID) que identifica o registro a ser atualizado.
   * @param array $data Um array de dados a serem atualizados, onde as chaves representam os campos da tabela.
   * @return bool Retorna true em caso de sucesso ou false em caso de falha.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function update(string $column, int $id, array $data): bool
  {
    try {
      $connection = Database::getInstance();
      $arrayFields = implode('=?,', array_keys($data)) . '=?';
      $sql = "UPDATE {$this->table} SET {$arrayFields} WHERE {$column} = ?";
      $prepare = $connection->prepare($sql);
      return $prepare->execute([...array_values($data), $id]);
    } catch (PDOException $e) {
      throw new PDOException("Error on updating register: " . $e->getMessage());
    }
  }

  /**
   * Insere um novo registro na tabela.
   *
   * @param array $data Um array associativo contendo os dados a serem inseridos, onde as chaves representam os campos da tabela.
   * @return int O ID do registro recém-inserido.
   * @throws PDOException Caso ocorra um erro ao executar a query.
   */
  public function insert(array $data): int
  {
    try {
      $connection = Database::getInstance();
      $fields = array_keys($data);
      $binds = array_pad([], count($fields), '?');

      $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';
      $stm = $connection->prepare($sql);
      $stm->execute(array_values($data));

      return (int) $connection->lastInsertId();
    } catch (PDOException $e) {
      throw new PDOException("Error on register insert: " . $e->getMessage());
    }
  }
}
