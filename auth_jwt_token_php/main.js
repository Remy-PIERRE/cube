// setup //
const url = "mettre l'url de l'api en fonction de votre installation !!!";

// on attend le chargement complet de la page //
window.addEventListener("load", initApp);

// fonction lancée au chargement de la page //
function initApp() {
	// on écoute le click sur le bouton d'envoie de formulaire //
	document.querySelector("#buttonLogIn").addEventListener("click", handleLogIn);

	// on écoute le click sur le bouton d'envoie de requête avec token intégré //
	document
		.querySelector("#buttonSendToken")
		.addEventListener("click", handleSendToken);
}

// login //
async function handleLogIn(event) {
	// on annule  le rechargement de la page à la validation d'un formulaire //
	event.preventDefault();

	// on récupère la valeur de l'input //
	const userNameValue = document.querySelector("#inputUserName").value;

	// on nettoie la valeur (espaces inutiles, injections HTML, ...) //
	const userName = sanitizeValue(userNameValue);

	// on vérifie que la valeur est valide //
	if (!userName || userName === "") {
		return console.log("Votre identifiant n'est pas valide !");
	}

	// tout est bon, on envoie la requête //
	try {
		const response = await fetch(url, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({ userName }),
		});

		// la réponse est en JSON => on la transforme en objet JS exploitable //
		const data = await response.json();

		// on emet une erreur si la réponse n'est pas valide //
		if (data.status !== "success") {
			throw newError(data.message);
		}

		// la réponse est valide, on stock le token dans le local storage //
		localStorage.setItem("token", data.token);

		// on affiche le token dans la console //
		console.log("Token : ", data.token);
	} catch (error) {
		// on affiche l'erreur dans la console //
		console.log("Erreur avec la requête POST : ", error);
	}
}

// fonction pour le nettoyage de la valeur //
function sanitizeValue(value) {
	return value.trim().replaceAll(/(&nbsp;|<([^>]+)>)/gi, "");
}

// send token //
async function handleSendToken() {
	// on récupère le token enregistré dans le local storage //
	const token = localStorage.getItem("token");

	// si il n'est pas présent //
	if (!token) {
		return console.log("Le token n'est pas présent !");
	}

	// on envoie notre requpete contenant le token pour s'authentifier auprès de l'API //
	try {
		const response = await fetch(url, {
			method: "GET",
			headers: {
				// c'est ici qu'est placé le token //
				Authorization: "Bearer " + token,
			},
		});

		// la réponse est en JSON => on la transforme en objet JS exploitable //
		const data = await response.json();

		// on emet une erreur si la réponse n'est pas valide //
		if (data.status !== "success") {
			throw newError(data.message);
		}

		// on affiche la réponse dans la console //
		console.log("Le token est validé pour : ", data.data.userName);
	} catch (error) {
		// on affiche l'erreur dans la console //
		console.log("Erreur avec la requête GET : ", error);
	}
}
