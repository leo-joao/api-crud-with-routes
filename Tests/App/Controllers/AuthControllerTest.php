<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;
use App\Models\UserModel;
use App\Helpers\PasswordHasher;

class AuthControllerTest extends TestCase
{
  private AuthController $authController;
  private $userModel; // Alterado para um tipo mais genérico

  protected function setUp(): void
  {
    $this->authController = new AuthController();
    $this->userModel = $this->createMock(UserModel::class);
    // Atribuir o modelo simulado ao controlador
    $this->authController->userModel = $this->userModel;
  }

  public function testLoginWithValidCredentials()
  {
    // Simula dados de entrada
    $_POST['login'] = 'valid_user';
    $_POST['pass'] = 'valid_pass';

    // Simula o retorno do método getUser
    $this->userModel->expects($this->once())
      ->method('getUser')
      ->with('valid_user')
      ->willReturn([$this->createMockUser('hashed_password')]);

    // Simula a verificação de senha
    PasswordHasher::method('verifyPassword')
      ->willReturn(true);

    // Executa o método login
    $this->authController->login();

    // Verifica a saída
    $this->expectOutputString('{"success":true}');
  }

  public function testLoginWithInvalidCredentials()
  {
    // Simula dados de entrada
    $_POST['login'] = 'invalid_user';
    $_POST['pass'] = 'invalid_pass';

    // Simula o retorno do método getUser com usuário não encontrado
    $this->userModel->expects($this->once())
      ->method('getUser')
      ->with('invalid_user')
      ->willReturn([]);

    // Executa o método login
    $this->authController->login();

    // Verifica a saída de erro
    $this->expectOutputString('{"success":false,"message":"Wrong credentials"}');
  }

  public function testLogoffRemovesAuthToken()
  {
    // Executa o método logoff
    $this->authController->logoff();

    // Verifica se o cookie foi removido
    $this->assertEquals('', $_COOKIE['auth_token']);
  }

  private function createMockUser($hashedPassword)
  {
    $user = $this->createMock(stdClass::class);
    $user->method('getPass')->willReturn($hashedPassword);
    return $user;
  }
}
