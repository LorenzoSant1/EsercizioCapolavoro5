<?php
session_start(); // Inizia una sessione per gestire i dati utente (login, carrello, etc.)
require 'DB/config.php'; // Include la connessione al database

// Recupera i primi 3 prodotti dal database
$stmt = $pdo->query("SELECT * FROM prodotti LIMIT 3"); // Query SQL per ottenere solo 3 prodotti
$prodotti = $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera i dati dei prodotti come array associativo
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuti su VibeWear</title>
    <link rel="stylesheet" href="css/stylecatalog.css"> <!-- Link al CSS principale -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Icone Boxicons -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Poetsen+One&family=Sriracha&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet"> <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> <!-- Font Google -->
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet"> <!-- Font Awesome -->
</head>
<body>
<header style="background-image: linear-gradient(to top, #fdcbf1 0%, #fdcbf1 1%, #e6dee9 100%);">
    <!-- Header con logo e icone per login, carrello e logout -->
    <h1 class="logo">VibeWear</h1>
    <div class="header-icons">
        <a href="login.php" class="login-icon"><i class='bx bxs-user'></i></a> <!-- Icona login -->
        <a href="cart.php" class="cart-icon"><i class='bx bx-shopping-bag'></i></a> <!-- Icona carrello -->
        <?php if (isset($_SESSION['utente_id'])): ?>
            <!-- Se l'utente è loggato, mostra l'icona di logout -->
            <a href="logout.php" class="logout-icon"><i class="fad fa-sign-out-alt"></i></a>
        <?php endif; ?>
    </div>
</header>

<section class="container" style="padding-top: 160px; text-align: center;">
    <!-- Sezione introduttiva con benvenuto e call-to-action -->
    <h1 style="font-size: 60px; margin-bottom: 20px;">Benvenuti su VibeWear</h1>
    <p style="font-size: 22px; max-width: 700px; margin: 0 auto; line-height: 1.6;">
        Scopri la nostra collezione esclusiva di sneakers che uniscono stile, comfort e tendenza. Preparati a fare il passo giusto con VibeWear.
    </p>
    <a href="catalog.php" class="btn-buy" style="margin-top: 40px; font-size: 20px;">Scopri il Catalogo</a> <!-- Link per accedere al catalogo -->
</section>

<section class="shop container">
    <h1 class="section-title">Perché scegliere VibeWear?</h1>
    <div class="shop-content" style="text-align: center;">
        <!-- Sezione con vantaggi del brand -->
        <div class="product-box" style="padding: 30px;">
            <i class='bx bx-star' style="font-size: 48px; color: var(--main-color);"></i>
            <h2 class="product-title">Qualità Premium</h2>
            <p>Materiali selezionati e design curato nei minimi dettagli.</p>
        </div>
        <div class="product-box" style="padding: 30px;">
            <i class='bx bx-run' style="font-size: 48px; color: var(--main-color);"></i>
            <h2 class="product-title">Comfort Assoluto</h2>
            <p>Sneakers ideate per offrirti il massimo della comodità, ogni giorno.</p>
        </div>
        <div class="product-box" style="padding: 30px;">
            <i class='bx bx-trending-up' style="font-size: 48px; color: var(--main-color);"></i>
            <h2 class="product-title">Stile in Evoluzione</h2>
            <p>Rimani sempre al passo con le ultime tendenze della moda urbana.</p>
        </div>
    </div>
</section>

<!-- Sezione con i prodotti in evidenza -->
<section class="featured-products container">
    <h1 class="section-title">Prodotti in Evidenza</h1>
    <div class="featured-products-content" style="display: flex; justify-content: center; gap: 20px;">
        <!-- Ciclo attraverso i prodotti per visualizzarli -->
        <?php foreach ($prodotti as $prodotto): ?>
            <div class="product-box" data-id="<?= $prodotto['id'] ?>">
                <!-- Visualizzazione immagine, nome e prezzo per ogni prodotto -->
                <img src="<?= htmlspecialchars($prodotto['immagine']) ?>" alt="<?= htmlspecialchars($prodotto['nome']) ?>" class="product-img">
                <h2 class="product-title"><?= htmlspecialchars($prodotto['nome']) ?></h2>
                <span class="price">€<?= number_format($prodotto['prezzo'], 2) ?></span>
                <!-- Link per vedere il dettaglio del prodotto -->
                <a href="product.php?id=<?= $prodotto['id'] ?>">
                    <i class='bx bx-shopping-bag add-cart'></i>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<footer>
    <!-- Footer con copyright -->
    <div class="footer-content">
        <div class="footer-nav">
            <div class="footer-legal">
                <p>&copy; 2025 VibeWear. Tutti i diritti riservati.</p> <!-- Copyright -->
            </div>
        </div>
    </div>
</footer>

</body>
</html>
