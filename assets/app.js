const pokeNameInputElement = document.querySelector(".js-pokename")
const pokemonListElement = document.querySelector(".js-list")

pokeNameInputElement.addEventListener("input", () => {
	Array.from(pokemonListElement.children).filter(elm => {
		if(!elm.id.includes(pokeNameInputElement.value.toLowerCase().trim())){
			if(!Array.from(elm.classList).includes("hidden")){
				elm.classList.add("hidden")
			}
		}else{
			elm.classList.remove("hidden")
		}
	})
})