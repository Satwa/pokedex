<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();

if(isset($_GET["name"]) && !empty($_GET["name"])){
	$pokemon =  $api->getPokemonList();
	print_r($pokemon);

	if(!array_key_exists("error", $pokemon)){
		/* array_filter($pokemon, function($elm){
			print_r($elm);
		}); */
	}else{ // we also should set header http status to 500
		echo json_encode([
			"error" => "Internal Server Error"
		]);
	}
}else{
	echo json_encode([
		"error" => "Missing parameters"
	]);
}