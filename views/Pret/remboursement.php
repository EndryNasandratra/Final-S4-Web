<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Remboursements</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script>
        // Charger la liste des remboursements
        function chargerRemboursements() {
            fetch('/ws/remboursements')
                .then(res => res.json())
                .then(data => {
                    let tbody = document.getElementById('remboursements-body');
                    tbody.innerHTML = '';
                    data.forEach(remb => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${remb.id}</td>
                                <td>${remb.id_pret}</td>
                                <td>${remb.montant_retour}</td>
                                <td>${remb.date_retour}</td>
                                <td>${remb.client_nom} ${remb.client_prenom}</td>
                                <td>${remb.employe_nom} ${remb.employe_prenom}</td>
                                <td>
                                    <button onclick="remplirForm(${remb.id})">Modifier</button>
                                    <button onclick="supprimerRemboursement(${remb.id})">Supprimer</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        // Ajouter ou modifier un remboursement
        function soumettreForm(event) {
            event.preventDefault();
            let id = document.getElementById('remb-id').value;
            let method = id ? 'PUT' : 'POST';
            let url = '/ws/remboursements' + (id ? '/' + id : '');
            let data = {
                id_pret: document.getElementById('id_pret').value,
                montant_retour: document.getElementById('montant_retour').value,
                date_retour: document.getElementById('date_retour').value
            };

            fetch(url, {
                method: method,
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    chargerRemboursements();
                    document.getElementById('remb-form').reset();
                    document.getElementById('remb-id').value = '';
                } else {
                    alert(res.error || 'Erreur');
                }
            });
        }

        // Remplir le formulaire pour modification
        function remplirForm(id) {
            fetch('/ws/remboursements/' + id)
                .then(res => res.json())
                .then(remb => {
                    document.getElementById('remb-id').value = remb.id;
                    document.getElementById('id_pret').value = remb.id_pret;
                    document.getElementById('montant_retour').value = remb.montant_retour;
                    document.getElementById('date_retour').value = remb.date_retour;
                });
        }

        // Supprimer un remboursement
        function supprimerRemboursement(id) {
            if (!confirm('Supprimer ce remboursement ?')) return;
            fetch('/ws/remboursements/' + id, {method: 'DELETE'})
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        chargerRemboursements();
                    } else {
                        alert(res.error || 'Erreur');
                    }
                });
        }

        window.onload = chargerRemboursements;
    </script>
</head>
<body>
    <h1>Gestion des remboursements</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Prêt</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Client</th>
                <th>Employé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="remboursements-body"></tbody>
    </table>

    <h2 id="form-title">Ajouter / Modifier un remboursement</h2>
    <form id="remb-form" onsubmit="soumettreForm(event)">
        <input type="hidden" id="remb-id">
        <label>ID Prêt : <input type="number" id="id_pret" required></label><br>
        <label>Montant : <input type="number" step="0.01" id="montant_retour" required></label><br>
        <label>Date : <input type="date" id="date_retour" required></label><br>
        <button type="submit">Valider</button>
        <button type="reset" onclick="document.getElementById('remb-id').value=''">Annuler</button>
    </form>
</body>
</html>