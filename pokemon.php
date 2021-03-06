<?php
require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();

if(!isset($_GET['name']) || empty($_GET['name'])){
	header('Location: /404');
	return;
}
$cacheManager = new Optimisme\Cache('pokemon-'.$_GET['name']); // dynamic cache per pokemon

if($cacheManager->open()):
	$pokemon = $api->getPokemonDetails($_GET['name']);

	if(array_key_exists('error', $pokemon)){
		header('Location: /404');
		return;	
	}

	$pokemon->name = ucwords(str_replace('-', ' ', $pokemon->name));

	// Get abilities details
	foreach($pokemon->abilities as $index => $ability){
		$pokemon->abilities[$index] = $api->getAbilityDetails($ability->ability->name);
	}

?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $pokemon->name; ?> is in your Pokedex</title>

		<link rel="stylesheet" href="/assets/app.css">
	</head>
	<body>
		<h1><a href="/" title="Back to Pokedex home">Pokedex</a></h1>

		<h3><?= $pokemon->name; ?> (<?= ucfirst($pokemon->types[0]->type->name); ?>)</h3>
		
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
					<div class="screen-inner ovs">
						<?php 
							foreach($pokemon->sprites as $axis => $sprite): 
								if($sprite === null) continue; // jump to next iteration if no sprite on this occurence
						?>
							<img src="<?= $sprite ?>" alt="<?= $pokemon->name ?> sprite with <?= ucwords(str_replace('_', ' ', $axis)); ?> form" class="sprite">
						<?php 
							break; // once we found a sprite, we stop the loop
							endforeach; 
						?>

						<h4>Stats</h4>
						<ul>
							<?php foreach($pokemon->stats as $stat): ?>
								<li><?= ucwords(str_replace('-', ' ', $stat->stat->name)); ?>: <?= $stat->base_stat ?> (<?= $stat->effort; ?> EV)</li>
							<?php endforeach; ?>
						</ul>

						<h4>Abilities</h4>
						<ul>
							<?php foreach($pokemon->abilities as $ability): ?>
								<li><?= ucwords(str_replace('-', ' ', $ability->name)); ?></li>
								<ul>
									<li><?= $ability->effect_entries[0]->short_effect; ?></li>
								</ul>
							<?php endforeach; ?>
						</ul>

						<h4>Moves</h4>
						<ul>
							<?php foreach($pokemon->moves as $move): ?>
								<li>
									<a href="/move/<?= $move->move->name; ?>" title="Get more info on <?= $move->move->name; ?> move">
										<?= ucwords(str_replace('-', ' ', $move->move->name)); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>

						<h4>Appears in</h4>
						<ul>
							<?php foreach($pokemon->game_indices as $game): ?>
								<li><?= ucwords(str_replace('-', ' ', $game->version->name)); ?></li>
							<?php endforeach; ?>
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