<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>Login Test</title>
</head>

<body>
  <h1>Connexion</h1>
  <div id="error-message" style="color:red; display:none;"></div>

  <form id="login-form" method="POST" action="/login">
    <input type="email" name="email" placeholder="Email" required /><br />
    <input type="password" name="password" placeholder="Mot de passe" required /><br />
    <button type="submit">Se connecter</button>
  </form>


  <script>
    document.getElementById('login-form').addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const errorMessage = document.getElementById('error-message');

      fetch('http://localhost/ITU/S4/Final-S4-Web/ws/login', {
          method: 'POST',
          body: formData
        })

        .then(response => {
          if (!response.ok) throw new Error('Réponse HTTP non OK');
          return response.json();
        })
        .then(data => {
          if (data.success) {
            window.location.href = data.redirect;
          } else {
            errorMessage.textContent = data.error || 'Erreur inconnue.';
            errorMessage.style.display = 'block';
          }
        })
        .catch(err => {
          errorMessage.textContent = 'Erreur réseau ou JSON : ' + err.message;
          errorMessage.style.display = 'block';
        });
    });
  </script>
</body>

</html>