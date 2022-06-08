<?php
// gebruik sessies
session_start();
// als de user niet is ingelogd stuur naar deze pagina:
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../index.html');
	exit;
}

// kijk of id parameter bestaat voordat je verder gaat
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once 'config.php';
    
    // bereid select statement voor
    $sql = "SELECT * FROM account WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // maak variables aan de prepared statement vast als parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // zet parameters
        $param_id = trim($_GET["id"]);
        
        // probeer prepared statement te runnen
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* krijg resultaat row als een associative array. omdat de resultaat set
                een row heeft, hoeven we geen while loop te gebruiken */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // individuele informatie krijgen
                $username = $row["username"];
                $password = $row["password"];
                $email = $row["email"];
                $telefoon = $row["telefoon"];
                $datum = $row["datum"];
                $geslacht = $row["geslacht"];
            } else{
                // url klopt niet redirect
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // sluit statement
    mysqli_stmt_close($stmt);
} else{
    // URL heeft geen id parameter. Redirect naar error pagina
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link href="../style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
                    <div class="form-group">
                        <label>Naam/Adres</label>
                        <p class="form-control-static"><?php echo $row["username"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Wachtwoord</label>
                        <p class="form-control-static"><?php echo $row["password"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <p class="form-control-static"><?php echo $row["email"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Telefoon Nummer</label>
                        <p class="form-control-static"><?php echo $row["telefoon"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Geboorte Datum</label>
                        <p class="form-control-static"><?php echo $row["datum"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Geslacht</label>
                        <p class="form-control-static"><?php echo $row["geslacht"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Terug</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>