<?php

namespace Tests\App\Models;

use App\Models\BaseModel;
use App\Database\Database;
use PDO;
use PHPUnit\Framework\TestCase;

class BaseModelTest extends TestCase
{
  private $model;
  private $pdoMock;

  protected function setUp(): void
  {
    // Cria um mock para a classe PDO
    $this->pdoMock = $this->createMock(PDO::class);

    // Cria um mock para a classe Database
    $dbMock = $this->createMock(Database::class);
    $dbMock->method('getInstance')->willReturn($this->pdoMock);

    // Instancia o modelo
    $this->model = new class extends BaseModel {
      protected $table = 'test_table'; // Tabela fictícia para testes
    };
  }

  public function testInsert()
  {
    // Define o retorno esperado para lastInsertId
    $this->pdoMock->method('lastInsertId')->willReturn('1');

    // Define o comportamento do mock para prepare e execute
    $stmtMock = $this->createMock(\PDOStatement::class);
    $stmtMock->method('execute')->willReturn(true);

    // Prepara o mock de prepare para retornar o stmtMock
    $this->pdoMock->method('prepare')->willReturn($stmtMock);

    // Dados de teste
    $data = ['column1' => 'value1', 'column2' => 'value2'];

    // Chama o método insert
    $result = $this->model->insert($data);

    // Verifica se o ID retornado é o esperado
    $this->assertEquals(1, $result);
  }

  public function testGet()
  {
    // Mock para o resultado da query
    $this->pdoMock->method('query')->willReturn($this->createMock(\PDOStatement::class));

    // Configura o comportamento para o fetchAll
    $this->pdoMock->method('query')->willReturnSelf();
    $this->pdoMock->method('fetchAll')->willReturn([]);

    // Chama o método get
    $result = $this->model->get();

    // Verifica se o resultado é um array
    $this->assertIsArray($result);
  }

  // public function testUpdate()
  // {
  //   // Mock para o resultado da query
  //   $this->pdoMock->method('query')->willReturn($this->createMock(\PDOStatement::class));

  //   // Configura o comportamento para o fetchAll
  //   $this->pdoMock->method('query')->willReturnSelf();
  //   $this->pdoMock->method('fetchAll')->willReturn([]);

  //   // Chama o método get
  //   $result = $this->model->get();

  //   // Verifica se o resultado é um array
  //   $this->assertIsArray($result);
  // }

  // Adicione testes adicionais para delete, update, etc.
}
