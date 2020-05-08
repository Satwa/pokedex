<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';
require_once 'lib/pokeapi.php';

$api = new Satwa\PokeAPIClient();

if(isset($_GET["name"]) && !empty($_GET["name"])){
	$pokemon =  $api->getPokemonList();

	$filtered = [];

	if(!array_key_exists("error", $pokemon)){
		array_filter($pokemon->results, function($item){
			global $filtered;
			if(strpos($item->name, $_GET["name"]) !== false){
				$filtered[] = $item;
			}
		}); 
		echo json_encode($filtered);
	}else{
		http_response_code(500);
	}
}else{
	http_response_code(400);
	echo json_encode([
		"error" => "Missing parameters"
	]);
}