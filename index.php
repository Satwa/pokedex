<?php
require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();
$cacheManager = new Optimisme\Cache(null, 604800);
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
		<h1><a href="/" title="Back to Pokedex home">Pokedex</a></h1>

		<h3>Pokemon List</h3>
		<div class="box">
			<div class="switch-container">
				<div class="joy-con-left">
					<div class="ellipse-button"></div>
					<div class="analog-left"></div>
					<div class="d-pad-container-left">
						<div class="d-pad-top"></div>
						<div class="d-pad-left"></div>
						<div class="d-pad-right"></div>
						<div class="d-pad-bottom"></div>
					</div>
					<div class="square-button"></div>
				</div>
				<div class="joy-con-right">
					<div class="analog-right"></div>
					<div class="d-pad-container-right">
						<div class="d-pad-top"></div>
						<div class="d-pad-left"></div>
						<div class="d-pad-right"></div>
						<div class="d-pad-bottom"></div>
					</div>
					<div class="circle-button-right"></div>
					<div class="d-pad-2-container">
						<div class="d-pad-2-vertical"></div>
						<div class="d-pad-2-horizontal"></div>
					</div>
				</div>
				<div class="screen-outer">
					<div class="screen-inner">
						<input type="text" name="pokemonName" id="PokemonName" class="pokename js-pokename" placeholder="Try Charmander">

						<div class="list js-list">
							<?php foreach($pokemonList as $pokemon): ?>
								<a href="/pokemon/<?= $pokemon->name ?>" class="item" id="<?= $pokemon->name ?>">
									<?= ucwords(str_replace('-', ' ', $pokemon->name)); ?>
								</a>
							<?php endforeach; ?>
						</div>

					</div>
				</div>
			</div>
		</div>

		<script src="/assets/app.js"></script>
	</body>
	</html>
<?php 
$cacheManager->save();
endif;	
?>