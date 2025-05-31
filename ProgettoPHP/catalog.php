<?php
session_start(); // Avvia la sessione per gestire i dati utente durante la navigazione
require 'DB/config.php'; // Include il file di configurazione per la connessione al database

// Recupera i prodotti dal database
$stmt = $pdo->query("SELECT * FROM prodotti"); // Esegue una query per selezionare tutti i prodotti
$prodotti = $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera i risultati della query come array associativo
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Prodotti</title>
    <link rel="stylesheet" href="css/stylecatalog.css"> <!-- Stile CSS per la pagina del catalogo -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons per le icone -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Poetsen+One&family=Sriracha&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet"> <!-- Font per la tipografia -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> <!-- Font per il logo -->
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet"> <!-- Font Awesome per le icone -->
</head>
<body>
<header style="background-image: linear-gradient(to top, #fdcbf1 0%, #fdcbf1 1%, #e6dee9 100%);">
    <h1 class="logo">VibeWear</h1> <!-- Logo del sito -->
    <div class="header-icons">
        <a href="index.php" class="home-icon"><i class="fas fa-home"></i></a> <!-- Icona per tornare alla home -->

        <?php if (!isset($_SESSION['utente_id'])): ?>
            <!-- Se l'utente non è loggato, mostra l'icona del login -->
            <a href="login.php" class="login-icon"><i class='bx bxs-user'></i></a>
        <?php endif; ?>

        <!-- Icona carrello -->
        <a href="cart.php" class="cart-icon"><i class='bx bx-shopping-bag'></i></a>

        <?php if (isset($_SESSION['utente_id'])): ?>
            <!-- Se l'utente è loggato, mostra l'icona per il logout -->
            <a href="logout.php" class="logout-icon"><i class="fad fa-sign-out-alt"></i></a>
        <?php endif; ?>
    </div>
</header>

<section class="shop container">
    <h1 style="text-align: center; font-size: 45px; padding-top: 50px;">SNEAKERS</h1>
    <div class="shop-content">
        <?php foreach ($prodotti as $prodotto): ?>
            <!-- Ciclo attraverso i prodotti per mostrarli nel catalogo -->
            <div class="product-box" data-id="<?= $prodotto['id'] ?>">
                <!-- Mostra l'immagine del prodotto -->
                <img src="<?= htmlspecialchars($prodotto['immagine']) ?>" alt="<?= htmlspecialchars($prodotto['nome']) ?>" class="product-img">

                <!-- Mostra il nome e il prezzo del prodotto -->
                <h2 class="product-title"><?= htmlspecialchars($prodotto['nome']) ?></h2>
                <span class="price">€<?= number_format($prodotto['prezzo'], 2) ?></span>

                <!-- Link per visualizzare i dettagli del prodotto -->
                <a href="product.php?id=<?= $prodotto['id'] ?>">
                    <i class='bx bx-shopping-bag add-cart'></i>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<footer>
    <div class="footer-content">
        <div class="footer-nav">
            <div class="footer-legal">
                <!-- Dati di copyright -->
                <p>&copy; 2025 VibeWear.</p>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
