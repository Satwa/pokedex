<?php
namespace Satwa;
/**
 * PokeAPI dead-simple wrapper
 */


class PokeAPIClient {
	
	public function __construct(){
		$this->root = 'https://pokeapi.co/api/v2/';
	}

	/**
	 * Public function handlers
	 */

	// Pokemon-related queries
	public function getPokemonList(){
		return $this->doRequest($this->root . 'pokemon?limit=-1');
	}

	public function getPokemonDetails($name){
		return $this->doRequest($this->root . "pokemon/$name");
	}

	public function getAbility($name){
		return $this->doRequest($this->root . "ability/$name");
	}

	/**
	 * Private function handlers
	 */

	private function sanitizeUrl($url){
		// TODO: Sanitize URL (htmlentities...)
	}

	private function doRequest($url){ // no need to ask for method, everything is in GET
		$request = curl_init();

        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true); // redirct to new route in case of 301 redirect
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 10); // timeout after 10sec
        $data = curl_exec($request);
        $http_code = curl_getinfo($request,  CURLINFO_RESPONSE_CODE );
		curl_close($request);

		$data = json_decode($data);

		echo $http_code;
		echo $data;
		
        if ($http_code != 200) { // request not 200 OK
            return [
                'status'  => $http_code,
                'error'   => $data,
            ];
        }

        return $data;
	}

}