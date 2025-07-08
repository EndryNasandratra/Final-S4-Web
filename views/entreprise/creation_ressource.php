<?php
$api_url = "http://localhost/S4_WEB/Final_S4/Final_S4_Web/ws";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Ajouter une Ressource</title>
    <!-- Le style est une copie exacte de votre fichier precedent pour la coherence -->
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input,
        select,
        button {
            margin: 5px 0;
            /* Alignement vertical */
            padding: 10px;
            /* Un peu plus de padding pour une meilleure ergonomie */
            width: 100%;
            box-sizing: border-box;
            /* Important */
        }

        h1 {
            text-align: center;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="../.."> ← Retour a la liste</a>
        <h1>Ajouter une nouvelle ressource</h1>

        <div>
            <label for="id_type_ressource">Type de ressource</label>
            <select id="id_type_ressource">
                <option value="">Chargement...</option>
            </select>

            <label for="valeur">Valeur</label>
            <input type="number" id="valeur" placeholder="Ex: 50000.00" step="0.01" />

            <button onclick="ajouterRessource()">Ajouter la ressource</button>
        </div>
    </div>


    <script>
        const apiBase = "<?php echo $api_url; ?>";

        function ajax(method, url, data, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open(method, apiBase + url, true);
            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && (xhr.status === 200 || xhr.status === 201)) {
                    console.log(xhr.responseText)
                    callback(JSON.parse(xhr.responseText));
                } else if (xhr.readyState === 4) {
                    console.error("Erreur AJAX:", xhr.status, xhr.responseText);
                    alert("Une erreur est survenue lors de la communication avec le serveur.");
                }
            };
            xhr.send(data);
        }

        function chargerTypesRessources() {
            ajax("GET", "/all_type_ressources", null, (data) => { // <-- VeRIFIEZ CETTE LIGNE
                const select = document.getElementById("id_type_ressource");
                select.innerHTML = '<option value="" disabled selected>-- Choisissez un type --</option>';

                data.forEach((type) => {
                    const option = document.createElement("option");
                    option.value = type.id;
                    option.textContent = type.libelle;
                    select.appendChild(option);
                });
            });
        }

        function resetForm() {
            document.getElementById("id_type_ressource").selectedIndex = 0;
            document.getElementById("valeur").value = "";
        }

        function ajouterRessource() {
            const id_type_ressource = document.getElementById("id_type_ressource").value;
            const valeur = document.getElementById("valeur").value;

            if (!id_type_ressource || !valeur) {
                alert("Veuillez remplir tous les champs.");
                return;
            }

            const data = `id_type_ressource=${id_type_ressource}&valeur=${valeur}`;

            ajax("POST", "/create_ressource", data, (response) => {
                alert(response.message || "Ressource ajoutee avec succes !");
                resetForm();
            });
        }

        document.addEventListener("DOMContentLoaded", chargerTypesRessources);
    </script>
</body>

</html>