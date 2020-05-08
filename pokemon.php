<?php
require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();

if(!isset($_GET['name']) || empty($_GET['name'])){
	header('Location: /404.html');
	return;
}
$cacheManager = new Optimisme\Cache('pokemon-'.$_GET['name']); // dynamic cache per pokemon

if($cacheManager->open()):
	$pokemon = $api->getPokemonDetails($_GET['name']);

	if(array_key_exists("error", $pokemon)){
		header('Location: /404.html');
		return;	
	}

	$pokemon->name = ucwords(str_replace('-', ' ', $pokemon->name));
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $pokemon->name; ?> is in your Pokedex</title>

		<link rel="stylesheet" href="assets/app.css">
	</head>
	<body>
		<h1>Pokedex</h1>

		<h3><?= $pokemon->name; ?> (<?= ucfirst($pokemon->types[0]->type->name); ?>)</h3>
		<?php 
			foreach($pokemon->sprites as $axis => $sprite): 
				if($sprite === null) continue;
		?>
			<img src="<?= $sprite ?>" alt="<?= $pokemon->name ?> sprite with <?= ucwords(str_replace('_', ' ', $axis)); ?> form" class="sprite">
		<?php endforeach; ?>

		<h4>Stats</h4>
		<ul>
			<?php foreach($pokemon->stats as $stat): ?>
				<li><?= ucwords(str_replace('-', ' ', $stat->stat->name)); ?>: <?= $stat->base_stat ?> (<?= $stat->effort ?> EV)</li>
			<?php endforeach; ?>
		</ul>

		<h4>Abilities</h4>
		<ul>
			<?php foreach($pokemon->abilities as $ability): ?>
				<li><?= ucwords(str_replace('-', ' ', $ability->ability->name)); ?></li>
			<?php endforeach; ?>
		</ul>

		<h4>Moves</h4>
		<ul>
			<?php foreach($pokemon->moves as $move): ?>
				<li><?= ucwords(str_replace('-', ' ', $move->move->name)); ?></li>
			<?php endforeach; ?>
		</ul>

		<h4>Appears in</h4>
		<ul>
			<?php foreach($pokemon->game_indices as $game): ?>
				<li><?= ucwords(str_replace('-', ' ', $game->version->name)); ?></li>
			<?php endforeach; ?>
		</ul>

	</body>
	</html>
<?php 
// $cacheManager->save();
endif;	
?>