<?php
require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();

if(!isset($_GET['name']) || empty($_GET['name'])){
	header('Location: /404.html');
	return;
}
$cacheManager = new Optimisme\Cache('move-'.$_GET['name'], 604800); // dynamic cache per pokemon, 7-day cache

if($cacheManager->open()):
	$move = $api->getMoveDetails($_GET['name']);

	if(array_key_exists('error', $move)){
		header('Location: /404.html');
		return;	
	}

	$move->name = ucwords(str_replace('-', ' ', $move->name));
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Learn more about <?= $move->name; ?> move</title>

		<link rel="stylesheet" href="assets/app.css">
	</head>
	<body>
		<h1><a href="/" title="Back to Pokedex home">Pokedex</a></h1>

		<h3><?= $move->name; ?> (<?= $move->type->name ?>)</h3>


		<h4>Stats</h4>
		<ul>
			<li>Accuracy: <?= $move->accuracy === null ? 0 : $move->accuracy ?>%</li>
			<li>Power points: <?= $move->pp   === null ? 0 : $move->pp       ?>%</li>
			<li>Base power: <?= $move->power  === null ? 0 : $move->power    ?>%</li>
			<li>Priority: <?= $move->priority === null ? 0 : $move->priority ?>%</li>
			<li>Affects: <?= ucwords(str_replace('-', ' ', $move->target->name)); ?></li>
		</ul>
	</body>
	</html>
<?php 
$cacheManager->save();
endif;	
?>