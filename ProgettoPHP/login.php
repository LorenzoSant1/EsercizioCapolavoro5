<?php
session_start(); // Avvia la sessione per gestire l'autenticazione dell'utente
require 'DB/config.php'; // Include la connessione al database

// Gestione del login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Se il metodo di richiesta è POST e l'utente ha inviato il modulo di login
    $email = $_POST['email']; // Recupera l'email inserita dall'utente
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email = ?"); // Prepara la query per cercare l'utente nel database
    $stmt->execute([$email]); // Esegue la query con l'email inserita
    $utente = $stmt->fetch(PDO::FETCH_ASSOC); // Recupera i dati dell'utente

    // Verifica se l'utente esiste e se la password è corretta
    if ($utente && password_verify($_POST['password'], $utente['password_hash'])) {
        // Se l'utente esiste e la password è corretta, avvia la sessione
        $_SESSION['utente_id'] = $utente['id'];
        $_SESSION['utente_nome'] = $utente['nome'];
        header("Location: catalog.php"); // Reindirizza l'utente alla pagina del catalogo
        exit; // Interrompe l'esecuzione del codice
    } else {
        // Se l'email o la password sono errati, mostra un messaggio di errore
        echo "<script>alert('Email o password errati.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/stylelogin.css"> <!-- Link al CSS per la pagina di login -->
</head>
<body>
<!-- Pulsante per tornare alla home -->
<a href="index.php" style="position: absolute; top: 20px; right: 20px; text-decoration: none;">
    <button style="background-color: #fdcbf1; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer;">
        🏠 Torna alla Home
    </button>
</a>

<div class="container">
    <h1>Login</h1>
    <!-- Form di login -->
    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" name="login" id="PayButton">Accedi</button> <!-- Pulsante per inviare il modulo -->
    </form>

    <p>Non hai un account? <a href="register.php">Registrati</a></p> <!-- Link alla pagina di registrazione -->
</div>
</body>
</html>
