<?php
require_once("templates/header.php");
// recuperar usuario logado
require_once("dao/UserDAO.php");
require_once("models/User.php");

$user = new User();
$userDAO = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(true);
### FIM
?>

<div id="main-container" class="container-fluid">
  <div class="offset-md-4 col-md-4 new-movie-container">
    <h1 class="page-title">Adicionar Filme</h1>
    <p class="page-description">Adicone sua critica e compartilhe com o mundo!</p>
    <form action="<?= $BASE_URL ?>movie_process.php" id="add-movie-form" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="type" value="create">
      <div class="form-group">
        <label for="title">Titulo: </label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Digite o titulo do seu filme">
      </div>
      <div class="form-group">
        <label for="image">Imagem: </label>
        <input type="file" class="form-control-file" id="image" name="image">
      </div>
      <div class="form-group">
        <label for="length">Duração: </label>
        <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme">
      </div>
      <div class="form-group">
        <label for="category">Categorias: </label>
        <select name="category" id="category" class="form-control">
          <option value="" class="disabled">Selecione</option>
          <option value="Ação">Ação</option>
          <option value="Drama">Drama</option>
          <option value="Comédia">Comédia</option>
          <option value="Fantasia">Fantasia</option>
          <option value="Ficção">Ficção</option>
        </select>
      </div>
      <div class="form-group">
        <label for="trailer">Trailer: </label>
        <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
      </div>
      <div class="form-group">
        <label for="description">Descricao: </label>
        <textarea name="description" id="description" rows="10" class="form-control" placeholder="Descreva o filme.."></textarea>
      </div>
      <input type="submit" name="submit" class="btn card-btn" value="Adicionar Filme">
    </form>
  </div>
</div>

<?php
require_once("templates/footer.php")
?>