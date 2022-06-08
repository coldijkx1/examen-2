<<?php
session_start();
// Include config file
require_once 'config.php';

/// kijk of de form data gesubmit is. de isset() functie kijkt of de form data bestaat
if ( !isset($_POST['username'], $_POST['password']) ) {
    // kon geen data krijgen
    exit('Please make sure you filled both the username and password form fields!');
    }
// hier berijden we sql injecties voor
if ($stmt = $con->prepare('SELECT id, password FROM account WHERE username = ?')) {
    // string = (s)
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // bewaart resultaat. kijkt of de user bestaat in de database
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // wachtwoord verifieren
        if ($_POST['password'] === $password) {
        // verificatie was een succes
        // maak een session omdat de user ingelogd is
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        header('Location: home.php');
        } else {
        // Fout wachtwoord
        echo 'verkeerde naam/wachtwoord!';
        }
        } else {
        // fout naam
        echo 'verkeerde naam/wachtwoord!';
        }
    $stmt->close();
    }

?>