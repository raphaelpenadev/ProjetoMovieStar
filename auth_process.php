<?php

require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
try {
  require_once("db.php");
} catch (Exception $e) {
  echo 'Exceção capturada: ',  $e->getMessage(), "\n";
}
$message = new Message($BASE_URL);
$userDAO = new userDAO($conn, $BASE_URL);

// Verifica o tipo de formulario

$type = filter_input(INPUT_POST, "type");

// Verificacao do tipo de formulario

if ($type === "register") {
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  $confirmPassword = filter_input(INPUT_POST, "confirmPassword");

  // Verificacao de dados minimos
  if ($name && $lastname && $email && $password) {
    // Verificar senhas
    if ($password === $confirmPassword) {

      // Verificar se o email ja esta cadastrado no sistema
      if ($userDAO->findByEmail($email) === false) {
        echo "Nenhum usuário foi encontrado";
      } else {
        // Enviar uma mensagem de erro caso o usuario ja tenha email cadastrado
        $message->setMessage("E-mail já cadastrado.", "error", "back");
      }
    } else {
      // Necessario uma mensagem de erro caso as senhas sejam diferentes
      $message->setMessage("As senhas não são iguais.", "error", "back");
    }
  } else {
    // Necessario uma mensagem de erro caso tenha algo faltando
    $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
  }
} else if ($type === "login") {
}
