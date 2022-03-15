<?php
require_once("templates/header.php");
require_once("dao/MovieDAO.php");

// DAO dos filmes
$movieDAO = new MovieDAO($conn, $BASE_URL);

$latestMovies = $movieDAO->getLatestMovies();

$actionMovies = $movieDAO->getMoviesByCategory("Ação");
$comedyMovies = $movieDAO->getMoviesByCategory("Comédia");
?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title">Filmes novos</h2>
  <p class="section-description">Veja as críticas dos ultimos filmes adicionados</p>
  <div class="movies-container">
    <?php foreach ($latestMovies as $movie) : ?>
      <?php require("templates/movie-card.php"); ?>
    <?php endforeach; ?>
    <?php if (count($latestMovies) === 0) : ?>
      <p class="empty-list">Ainda não há filmes cadastrados! </p>
    <?php endif; ?>
  </div>
  <h2 class="section-title">Ação</h2>
  <p class="section-description">Veja os melhores filmes de ação</p>
  <?php if (count($actionMovies) === 0) : ?>
    <p class="empty-list">Ainda não há filmes de ação cadastrados! </p>
  <?php endif; ?>
  <div class="movies-container">
    <?php foreach ($actionMovies as $movie) : ?>
      <?php require("templates/movie-card.php"); ?>
    <?php endforeach; ?>
  </div>
  <h2 class="section-title">Comédia</h2>
  <p class="section-description">Veja os melhores filmes de comédia</p>
  <?php if (count($comedyMovies) === 0) : ?>
    <p class="empty-list">Ainda não há filmes de comédia cadastrados! </p>
  <?php endif; ?>
  <div class="movies-container">
    <?php foreach ($comedyMovies as $movie) : ?>
      <?php require("templates/movie-card.php"); ?>
    <?php endforeach; ?>
  </div>

</div>

<?php
require_once("templates/footer.php")
?>