<?php
session_start();
session_destroy();
echo "<script>
    alert('Logout effettuato con successo!');
    window.location.href = 'login.php';
</script>";
exit;
