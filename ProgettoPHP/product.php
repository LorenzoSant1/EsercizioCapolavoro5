<?php
require 'DB/config.php'; // Connessione al database

// Recupera l'ID del prodotto dalla query string (se non presente, si imposta a 0)
$id = $_GET['id'] ?? 0;
// Prepara la query per selezionare il prodotto con l'ID fornito
$stmt = $pdo->prepare("SELECT * FROM prodotti WHERE id = ?");
$stmt->execute([$id]); // Esegue la query con l'ID del prodotto
$prodotto = $stmt->fetch(PDO::FETCH_ASSOC); // Recupera i dati del prodotto

// Se il prodotto non esiste, termina l'esecuzione con un messaggio di errore
if (!$prodotto) {
    die("Prodotto non trovato");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($prodotto['nome']) ?></title> <!-- Titolo dinamico basato sul nome del prodotto -->
    <link rel="stylesheet" href="css/styleproduct.css"> <!-- Link al file CSS per la pagina prodotto -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Poetsen+One&family=Sriracha&family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet"> <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> <!-- Font Google -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome per le icone -->
</head>
<body>

<header>
    <h1>VibeWear</h1> <!-- Nome del brand -->
    <a href="catalog.php" class="back-to-catalog"><i class="fas fa-arrow-left"></i> Torna al Catalogo</a> <!-- Link per tornare al catalogo -->
</header>

<div class="content">
    <main class="product-container">
        <div class="product-content">
            <h1><?= htmlspecialchars($prodotto['nome']) ?></h1> <!-- Nome del prodotto -->
            <img src="<?= htmlspecialchars($prodotto['immagine']) ?>" alt="<?= htmlspecialchars($prodotto['nome']) ?>" class="product-img"> <!-- Immagine del prodotto -->
            <p><?= htmlspecialchars($prodotto['descrizione']) ?></p> <!-- Descrizione del prodotto -->
            <p><strong>Prezzo: €<?= number_format($prodotto['prezzo'], 2) ?></strong></p> <!-- Prezzo formattato -->

            <!-- Modulo per aggiungere al carrello -->
            <form method="POST" action="cart.php">
                <label for="size">Taglia:</label>
                <select id="size" name="size" class="size-selector" required> <!-- Selezione taglia -->
                    <option value="">Seleziona una taglia</option>
                    <option value="41">41</option>
                    <option value="43">43</option>
                    <option value="37">37</option>
                    <option value="46">46</option>
                </select>
                <br>
                <label for="quantita">Quantità:</label>
                <input type="number" id="quantita" name="quantita" value="1" min="1" required> <!-- Selezione quantità -->

                <input type="hidden" name="product_id" value="<?= $prodotto['id'] ?>"> <!-- ID prodotto nascosto -->
                <button type="submit" name="add_to_cart" class="add-to-cart">Aggiungi al Carrello</button> <!-- Pulsante per aggiungere al carrello -->
            </form>
        </div>
    </main>
</div>

<footer>
    <div class="footer-content">
        <p>© 2025 VibeWear</p> <!-- Copyright -->
    </div>
</footer>

</body>
</html>
