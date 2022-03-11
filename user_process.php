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
$userDAO = new UserDAO($conn, $BASE_URL);

// Verifica o tipo de formulario

$type = filter_input(INPUT_POST, "type");

// Atualizar usuario
if ($type === "update") {

  // Resgata dados do usuario
  $userData = $userDAO->verifyToken();

  //Receber dados do post
  $name = filter_input(INPUT_POST, 'name');
  $lastname = filter_input(INPUT_POST, 'lastname');
  $email = filter_input(INPUT_POST, 'email');
  $bio = filter_input(INPUT_POST, 'bio');


  // Novo objeto de usuario
  $user = new User();

  //Preencher dados do usuario
  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;

  // Upload de imagens
  if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpeg", "image/jpg"];

    // Checar o tipo de imagem
    if (in_array($image["type"], $imageTypes)) {

      // Checar se é jpg
      if (in_array($image, $jpgArray)) {
        $imageFile = imagecreatefromjpeg($image["tmp_name"]);

        // Imagem no formato png
      } else {
        $imageFile = imagecreatefrompng($image["tmp_name"]);
      }

      $imageName = $user->imageGenerateName();

      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      $userData->image = $imageName;
    } else {
      $message->setMessage("Tipo de imagem inválido, insira png/jpg .", "error", "back");
    }
  }

  $userDAO->update($userData);


  // Atualizar senha do usuario
} else if ($type === "changepassword") {

  //Receber dados do post
  $password = filter_input(INPUT_POST, 'password');
  $confirmpassword = filter_input(INPUT_POST, 'confirmpassword');

  // Resgata dados do usuario
  $userData = $userDAO->verifyToken();
  $id = $userData->id;

  if ($password === $confirmpassword) {

    // Novo objeto de usuario
    $user = new User();
    $finalPassword = $user->generatePassword($password);

    $user->password = $finalPassword;
    $user->id = $id;

    $userDAO->changePassword($user);
  } else {
    $message->setMessage("As senhas não são iguais.", "error", "back");
  }
} else {
  $message->setMessage("Informações inválidas.", "error", "index.php");
}
