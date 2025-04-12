<?php
session_start();
require 'config2.php';

$page = basename($_SERVER['PHP_SELF']);
$marquee_text = 'Bienvenue sur notre site !';
$header_message_text = 'Message par défaut pour l\'en-tête.';
$why_text = 'Texte par défaut pour le "Pourquoi ce site ?"';

// Récupération depuis custom_content
$stmt = $pdo->prepare("SELECT section_name, section_content, marquee_text FROM custom_content WHERE page_name = ?");
$stmt->execute([$page]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($row['marquee_text'])) {
        $marquee_text = $row['marquee_text'];
    }
    if ($row['section_name'] === 'header_message') {
        $header_message_text = $row['section_content'];
    }
    if ($row['section_name'] === 'why_text') {
        $why_text = $row['section_content'];
    }
}

// Statistiques
$stmt = $pdo->query("SELECT COUNT(*) AS user_count FROM users");
$user_count = $stmt->fetch(PDO::FETCH_ASSOC)['user_count'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) AS portfolio_count FROM portfolios");
$portfolio_count = $stmt->fetch(PDO::FETCH_ASSOC)['portfolio_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Accueil | Site de Divertissement</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f9f9f9, #e9ecef);
      color: #333;
      line-height: 1.6;
      padding: 0 20px;
    }

    header {
      background: #007bff;
      color: white;
      padding: 40px 20px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    header h1 {
      font-size: 2.5em;
      margin-bottom: 10px;
      font-weight: bold;
    }

    header p {
      font-size: 1.1em;
      color: #ffe066;
    }

    marquee {
      background: #fff3cd;
      color: #856404;
      padding: 10px;
      font-weight: bold;
      font-size: 1em;
      border-radius: 5px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    main {
      max-width: 1000px;
      margin: 40px auto;
      padding: 0 20px;
    }

   section {
  margin-bottom: 40px;
  padding: 20px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

section:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

h2 {
  color: #0056b3;
  margin-bottom: 15px;
  font-size: 1.8em;
  font-weight: 700;
  position: relative;
}

h2::after {
  content: '';
  display: block;
  width: 50px;
  height: 3px;
  background-color: #28a745;
  margin-top: 8px;
  border-radius: 2px;
}

    .btn {
      display: inline-block;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 8px;
      margin: 10px 10px 0 0;
      font-size: 1.1em;
      transition: 0.3s ease;
      font-weight: 600;
    }

    .btn.primary {
      background: #28a745;
      color: white;
    }

    .btn.secondary {
      background: #17a2b8;
      color: white;
    }

    .btn.danger {
      background: #dc3545;
      color: white;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .user-welcome {
      font-weight: bold;
      color: #343a40;
      margin-bottom: 10px;
      font-size: 1.2em;
    }

    footer {
      background: #343a40;
      color: #ccc;
      text-align: center;
      padding: 20px;
      font-size: 0.9em;
      margin-top: 50px;
      border-radius: 10px;
    }

    footer a {
      color: #ccc;
      text-decoration: none;
    }

    footer a:hover {
      color: #fff;
    }

    @media screen and (max-width: 768px) {
      body {
        padding: 10px;
      }

      header h1 {
        font-size: 1.8em;
      }

      .btn {
        width: 100%;
        margin: 10px 0;
      }

      marquee {
        font-size: 0.9em;
      }

      main {
        padding: 0 10px;
      }

      .user-welcome {
        font-size: 1.1em;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Bienvenue sur notre site de divertissement</h1>
    <p><?= htmlspecialchars($header_message_text); ?></p> <!-- Affichage du message dans l'en-tête -->
      <marquee behavior="scroll" direction="left">
    <?= htmlspecialchars($marquee_text, ENT_QUOTES, 'UTF-8'); ?>
  </marquee>
  </header>



  <main>
    <section>
      <h2>Pourquoi ce site ?</h2>
      <p><?= htmlspecialchars($why_text); ?></p> <!-- Affichage du texte "Pourquoi ce site ?" -->
    
    </section>

    <section>
      <h2>Statistiques</h2>
      <p>Nombre d'utilisateurs enregistrés : <strong><?= $user_count; ?></strong></p>
         <p>Nombre de portfolios enregistrés : <strong><?= $portfolio_count; ?></strong></p>
    </section>

    <section>
      <?php if (isset($_SESSION["user_id"])): ?>
        <p class="user-welcome">Bienvenue, <?= htmlspecialchars($_SESSION["username"]); ?> !</p>
        <a href="dashboard.php" class="btn primary">Accéder à mon espace</a>
        <a href="logout.php" class="btn danger">Déconnexion</a>
      <?php else: ?>
        <a href="register.php" class="btn primary">S'inscrire</a>
        <a href="login.php" class="btn secondary">Se connecter</a>
      <?php endif; ?>
    </section>
  </main>

  <?php include('pages/footer.php'); ?>

</body>
</html>