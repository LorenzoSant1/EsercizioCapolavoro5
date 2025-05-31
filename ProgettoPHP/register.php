<?php
session_start(); // Avvia la sessione per gestire i dati utente durante la navigazione
require 'DB/config.php'; // Connessione al database

// Registrazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Estrazione dei dati dal form
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Criptazione della password

    // Inserimento dei dati nel database (tabella 'utenti')
    $stmt = $pdo->prepare("INSERT INTO utenti (nome, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $password]); // Esegue l'inserimento nel database

    // Recupera l'ID dell'utente appena registrato
    $utente_id = $pdo->lastInsertId();

    // Imposta i dati della sessione per il login automatico
    $_SESSION['utente_id'] = $utente_id;
    $_SESSION['utente_nome'] = $nome;

    // Notifica di successo con un alert e reindirizzamento alla pagina catalogo
    echo "<script>
            alert('Registrazione completata con successo!');
            window.location.href = 'catalog.php';
          </script>";
    exit; // Interrompe l'esecuzione del codice
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title> <!-- Titolo della pagina -->
    <link rel="stylesheet" href="css/stylelogin.css"> <!-- Link al file CSS per lo stile della pagina -->
</head>
<body>
<!-- Pulsante in alto a destra per tornare alla home -->
<a href="index.php" style="position: absolute; top: 20px; right: 20px; text-decoration: none;">
    <button style="background-color: #fdcbf1; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; cursor: pointer;">
        🏠 Torna alla Home
    </button>
</a>

<div class="container">
    <h1>Registrazione</h1> <!-- Titolo della sezione di registrazione -->
    <form method="POST">
        <!-- Campo per il nome -->
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome" required>
        </div>

        <!-- Campo per l'email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <!-- Campo per la password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <!-- Bottone per inviare il modulo -->
        <button type="submit" name="register" id="PayButton">Registrati</button>
    </form>

    <!-- Link per l'accesso se l'utente ha già un account -->
    <p>Hai già un account? <a href="login.php">Accedi</a></p>
</div>
</body>
</html>
