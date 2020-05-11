<?php
require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();

if(!isset($_GET['name']) || empty($_GET['name'])){
	header('Location: /404');
	return;
}
$cacheManager = new Optimisme\Cache('move-'.$_GET['name'], 604800); // dynamic cache per pokemon, 7-day cache

if($cacheManager->open()):
	$move = $api->getMoveDetails($_GET['name']);

	if(array_key_exists('error', $move)){
		header('Location: /404');
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

		<link rel="stylesheet" href="/assets/app.css">
	</head>
	<body>
		<h1><a href="/" title="Back to Pokedex home">Pokedex</a></h1>

		<h3><?= $move->name; ?> (<?= $move->type->name ?>)</h3>

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
					<div class="screen-inner vcenter">
						<ul>
							<li><?= $move->accuracy === null ? 0 : $move->accuracy ?>% Accuracy</li>
							<li><?= $move->pp       === null ? 0 : $move->pp       ?>% Power points</li>
							<li><?= $move->power    === null ? 0 : $move->power    ?>% Base power</li>
							<li><?= $move->priority === null ? 0 : $move->priority ?>% Priority</li>
							<li>Affects: <?= ucwords(str_replace('-', ' ', $move->target->name)); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</body>
	</html>
<?php 
$cacheManager->save();
endif;	
?>