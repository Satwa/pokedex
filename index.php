<?php
require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();
$cacheManager = new Optimisme\Cache();
if($cacheManager->open()):
	$pokemonList = $api->getPokemonList();
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pokedex</title>

		<link rel="stylesheet" href="assets/app.css">
	</head>
	<body>
		<h1>Pokedex</h1>

		<h3>Pokemon List</h3>
		<input type="text" name="pokemonName" id="PokemonName" class="js-pokename" placeholder="Try Charmander">
		<div class="list js-list">
			<?php foreach($pokemonList as $pokemon): ?>
				<a href="pokemon.php?name=<?= $pokemon->name ?>" class="item" id="<?= $pokemon->name ?>">
					<?= ucwords(str_replace('-', ' ', $pokemon->name)); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<script src="assets/app.js"></script>
	</body>
	</html>
<?php 
// DISABLED FOR DEV PURPOSE: $cacheManager->save();
endif;	
?>