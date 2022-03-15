<?php

require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("dao/MovieDAO.php");
try {
  require_once("db.php");
} catch (Exception $e) {
  echo 'Exceção capturada: ',  $e->getMessage(), "\n";
}

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");

// Resgata dados do usuario
$userData = $userDAO->verifyToken();

if ($type === "create") {
  // Receber dados dos inputs
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");

  $movie = new Movie();

  //Validacao minima de dados

  if (!empty($title) && !empty($description) && !empty($category)) {
    $movie->title = $title;
    $movie->description = $description;
    $movie->trailer  = $trailer;
    $movie->category = $category;
    $movie->length = $length;
    $movie->users_id = $userData->id;

    //Upload de imagem
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
      $image = $_FILES["image"];
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      $jpgArray = ["image/jpeg", "image/jpg"];

      // Checando o tipo da imagem
      if (in_array($image["type"], $imageTypes)) {

        // Checa se imagem é jpeg
        if (in_array($image["type"], $jpgArray)) {
          $imageFile = imagecreatefromjpeg($image["tmp_name"]);
        } else {
          $imageFile = imagecreatefrompng($image["tmp_name"]);
        }


        // Nome da imagem
        $imageName = $movie->imageGenerateName();

        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

        $movie->image = $imageName;
      } else {
        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
      }
    }

    $movieDAO->create($movie);
  } else {
    $message->setMessage("Você precisa adicionar pelo menos: titulo, descrição e categoria", "error", "back");
  }
} else if ($type === "delete") {
  // Receber dados do formulario
  $id = filter_input(INPUT_POST, "id");
  $movie = $movieDAO->findById($id);

  if ($movie) {
    // Verificar se o filme é do usuario
    if ($movie->user_id === $userData->id) {

      $movieDAO->destroy($movie->id);
    } else {
      $message->setMessage("Informações inválidas", "error", "index.php");
    }
  } else {
    $message->setMessage("Informações inválidas", "error", "index.php");
  }
} else {
  $message->setMessage("Informações inválidas", "error", "index.php");
}
