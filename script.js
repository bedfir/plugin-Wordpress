// Je Refuse
function refuser() {
  // Supprission du modal
  document.getElementById("modal_back").remove();
  // Supprission du style
  document.querySelector("#style-css").remove();
}

// jouer
function jouer() {
  // Récupérer le numero de chance via l'element Input
  let selectElem = document.getElementById("items");
  let result = selectElem.options[selectElem.selectedIndex].text;
  let numeroChance = document.getElementById("numChance").value;
  let output = document.getElementById("output");

  if (result != "Numero Chance ?") {
    document.getElementById("nav").remove();
    let button = document.createElement("button");
    button.innerHTML = "OK";
    button.onclick = refuser;
    document
      .getElementById("toggle")
      .insertAdjacentElement("beforeend", button);

    if (result == numeroChance) {
      output.textContent = "Bravo, c'est votre jour de chance";
    } else {
      output.textContent = "Malheureusement, ce n'est pas votre jour de chance";
    }
  } else {
    output.textContent = "Sélectionner un chiffre.";
  }
}
