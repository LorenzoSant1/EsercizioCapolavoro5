<?php
session_start();
require 'DB/config.php'; // Connessione al database

// Verifica che l'utente sia loggato
if (!isset($_SESSION['utente_id'])) {
    die("Errore: l'utente non è loggato.");
}

// Aggiungi al carrello
if (isset($_POST['add_to_cart'])) {
    $prodotto_id = $_POST['product_id'];  // ID del prodotto che l'utente vuole aggiungere
    $utente_id = $_SESSION['utente_id']; // ID dell'utente loggato
    $quantita = $_POST['quantita']; // Quantità scelta dell'articolo
    $numero = $_POST['size']; // Numero della taglia scelta

    // Verifica se il prodotto esiste nel database
    $stmt = $pdo->prepare("SELECT * FROM prodotti WHERE id = ?");
    $stmt->execute([$prodotto_id]);
    $prodotto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prodotto) {
        // Verifica se il prodotto con quella taglia è già nel carrello
        $stmt = $pdo->prepare("SELECT * FROM carrello WHERE prodotto_id = ? AND utente_id = ? AND numero = ?");
        $stmt->execute([$prodotto_id, $utente_id, $numero]);
        $carrello = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($carrello) {
            // Se il prodotto è già nel carrello, aggiorna la quantità
            $new_qty = $carrello['quantita'] + $quantita;
            $stmt = $pdo->prepare("UPDATE carrello SET quantita = ? WHERE prodotto_id = ? AND utente_id = ? AND numero = ?");
            $stmt->execute([$new_qty, $prodotto_id, $utente_id, $numero]);
        } else {
            // Se il prodotto non è nel carrello, aggiungilo
            $stmt = $pdo->prepare("INSERT INTO carrello (utente_id, prodotto_id, quantita, numero) VALUES (?, ?, ?, ?)");
            $stmt->execute([$utente_id, $prodotto_id, $quantita, $numero]);
        }

        // Aggiungi o aggiorna il prodotto nella sessione
        $_SESSION['carrello'][$prodotto_id][$numero] = isset($_SESSION['carrello'][$prodotto_id][$numero]) ? $_SESSION['carrello'][$prodotto_id][$numero] + $quantita : $quantita;

        // Reindirizza al carrello
        header('Location: cart.php');
        exit();
    } else {
        die("Errore: il prodotto non esiste.");
    }
}

// Rimuovi dal carrello
if (isset($_GET['remove'])) {
    $prodotto_id = $_GET['remove'];  // ID del prodotto da rimuovere
    $utente_id = $_SESSION['utente_id']; // ID dell'utente loggato

    // Verifica se il prodotto è nel carrello (nel database)
    $stmt = $pdo->prepare("SELECT * FROM carrello WHERE prodotto_id = ? AND utente_id = ?");
    $stmt->execute([$prodotto_id, $utente_id]);
    $carrello = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($carrello) {
        // Se la quantità è maggiore di 1, decrementa la quantità
        if ($carrello['quantita'] > 1) {
            $new_qty = $carrello['quantita'] - 1;
            $stmt = $pdo->prepare("UPDATE carrello SET quantita = ?
                WHERE prodotto_id = ? AND utente_id = ?");
            $stmt->execute([$new_qty, $prodotto_id, $utente_id]);
        } else {
            // Se la quantità è 1, rimuovi il prodotto dal carrello nel database
            $stmt = $pdo->prepare("DELETE FROM carrello WHERE prodotto_id = ? AND utente_id = ?");
            $stmt->execute([$prodotto_id, $utente_id]);
        }
    }

    // Rimuovi il prodotto anche dalla sessione
    unset($_SESSION['carrello'][$prodotto_id]);
}

// Recupera i dettagli dei prodotti nel carrello dalla sessione (aggiornata)
$prodotti = [];
$total_price = 0;
if (!empty($_SESSION['carrello'])) {
    $ids = [];
    foreach ($_SESSION['carrello'] as $prod_id => $sizes) {
        foreach ($sizes as $size => $qty) {
            $ids[] = $prod_id;
        }
    }

    // Controlla che l'array degli ID non sia vuoto prima di eseguire la query
    if (!empty($ids)) {
        $ids = implode(',', array_unique($ids));
        $stmt = $pdo->query("SELECT * FROM prodotti WHERE id IN ($ids)");
        $prodotti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcola il totale
        foreach ($prodotti as $prodotto) {
            foreach ($_SESSION['carrello'][$prodotto['id']] as $numero => $qty) {
                // Verifica che la quantità sia effettivamente un numero
                if (is_numeric($qty) && $qty > 0) {
                    $total_price += $prodotto['prezzo'] * $qty;
                }
            }
        }
    }
}

// Aggiungi la logica per applicare il codice sconto
$sconto_applicato = 0;
if (isset($_POST['discount_code'])) {
    $codice_sconto = $_POST['discount_code'];

    // Verifica se il codice sconto esiste nel database
    $stmt = $pdo->prepare("SELECT valore FROM sconti WHERE codice = ?");
    $stmt->execute([$codice_sconto]);
    $sconto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($sconto) {
        // Calcola lo sconto in percentuale
        $sconto_applicato = $sconto['valore'];
        // Applica lo sconto al totale
        $total_price = $total_price - ($total_price * ($sconto_applicato / 100));
    } else {
        echo "<p>Codice sconto non valido.</p>";
    }
}

// Gestisci l'azione "Acquista Ora"
if (isset($_POST['buy_now'])) {
    // Svuota il carrello dalla sessione
    unset($_SESSION['carrello']);
    // Mostra l'alert prima di reindirizzare
    echo "<script>
            alert('Ordine completato! Tornerai al catalogo.');
            window.location.href = 'catalog.php';
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello</title>
    <link rel="stylesheet" href="css/stylecart.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header style="background-image: linear-gradient(to top, #fdcbf1 0%, #fdcbf1 1%, #e6dee9 100%);">
    <h1 class="logo">VibeWear</h1>
    <nav>
        <div class="back-to-catalog">
            <a href="catalog.php" class="back-to-catalog"><i class="fas fa-arrow-left"></i> Torna al Catalogo</a>
        </div>
    </nav>
</header>
<!-- HTML per visualizzare il carrello e il totale sconto -->
<main>
    <section class="cart">
        <h2 class="cart-title">Il tuo Carrello</h2>
        <div class="cart-content">
            <?php if (empty($prodotti)): ?>
                <p>Il carrello è vuoto.</p>
            <?php else: ?>
                <?php foreach ($prodotti as $prodotto): ?>
                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($prodotto['immagine']) ?>" alt="<?= htmlspecialchars($prodotto['nome']) ?>" class="cart-item-img">
                        <div class="cart-item-info">
                            <h3><?= htmlspecialchars($prodotto['nome']) ?></h3>
                            <?php foreach ($_SESSION['carrello'][$prodotto['id']] as $numero => $qty): ?>
                                <p>Quantità (Numero <?= htmlspecialchars($numero) ?>): <?= $qty ?></p>
                            <?php endforeach; ?>
                            <p><strong>€<?= number_format($prodotto['prezzo'], 2) ?></strong></p>
                            <a href="cart.php?remove=<?= $prodotto['id'] ?>" class="remove-item">Rimuovi</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="total">
            <span class="total-title">Totale:</span>
            <span class="total-price">€<?= number_format($total_price, 2) ?></span>
        </div>

        <div class="discount-code">
            <form method="POST" action="cart.php">
                <input type="text" name="discount_code" id="discount-code" placeholder="Inserisci codice sconto" class="discount-input">
                <button type="submit" class="apply-discount">Applica Codice</button>
            </form>
        </div>

        <form method="POST" action="cart.php">
            <button type="submit" name="buy_now" class="btn-buy">Acquista Ora</button>
        </form>
    </section>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 VibeWear</p>
    </div>
</footer>
</body>
</html>
