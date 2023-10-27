
let chipid = "";

// let ParametroURL = new URLSearchParams(location.search);
// chipid = ParametroURL.get('chipid');
// console.log("Chipid="+chipid)


document.addEventListener("DOMContentLoaded", function(event){
	// buscamos el chipid
	chipid = document.querySelector("#chipid").innerHTML;
	console.log("chipid: " + chipid);
})

document.addEventListener("DOMContentLoaded", () => {
			
			Clima().then( data => {

					var datosLugar = data.find(function(element){
						return chipid === element.chipid
					})
					
					BotonesDatos(datosLugar);
			
			})
		})

//tengo la info del clima
	async function Clima(){

			const response = await fetch("https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations")
			const data = await response.json()
			
			return data;
		}

	function BotonesDatos(clim){
		let tpl = document.querySelector("#botones")
	

		document.querySelector(".clima-ubicacion").innerHTML= '<i class="fa-solid fa-location-dot"></i>'+clim.ubicacion
		// clon.querySelector(".clima-visitas").innerHTML = clim.visitas+'<i class="fa-solid fa-person"></i>'
		document.querySelector(".clima-apodo").innerHTML = clim.apodo
	}



