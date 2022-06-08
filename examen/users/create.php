<?php
// gebruik sessies
session_start();
// als de user niet is ingelogd stuur naar deze pagina:
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../index.html');
	exit;
}

// gebruik config
require_once 'config.php';
 
// variabelen difineren
$username = $password = $email = $telefoon = $datum = $geslacht = "";
$username_err = $password_err = $email_err = $telefoon_err = $datum_err = $geslacht_err = "";
 
// proces de form data na submit
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // username valideren
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Please enter a username.";
    } elseif(!filter_var(trim($_POST["username"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $username_err = 'Please enter a valid username.';
    } else{
        $username = $input_username;
    }

     // wachtwoord valideren
     $input_password = trim($_POST["password"]);
     if(empty($input_password)){
         $password_err = "Please enter a password.";
     } elseif(!filter_var(trim($_POST["password"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
         $password_err = 'Please enter a valid password.';
     } else{
         $password = $input_password;
     }

     // email valideren
     $input_email = trim($_POST["email"]);
     if(empty($input_email)){
         $email_err = "Please enter a email.";
     } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
         $email_err = 'vul een geldige email in.';
     } else{
         $email = $input_email;
     }

     // telefoon nummer valideren
    $input_telefoon = trim($_POST["telefoon"]);
    if(empty($input_telefoon)){
        $telefoon_err = "vul een goed telefoon nummer in.";     
    } elseif(!ctype_digit($input_telefoon)){
        $telefoon_err = 'Please enter a positive integer value.';
    } else{
        $telefoon = $input_telefoon;
    }

    // datum valideren
    $input_datum = trim($_POST["datum"]);
    if(empty($input_datum)){
        $datum_err = "vul een geldige datum in";     
    } elseif(!filter_var($input_datum)){
        $datum_err = 'Please enter a positive integer value.';
    } else{
        $datum = $input_datum;
    }

     // geslacht valideren
     $input_geslacht = trim($_POST["geslacht"]);
     if(empty($input_geslacht)){
         $geslacht_err = "Please enter the geslacht amount.";     
     } elseif(!filter_var($input_geslacht)){
         $geslacht_err = 'Please enter a positive integer value.';
     } else{
         $geslacht = $input_geslacht;
     }

    
    // zoek naar input errors voordat het de database ingaat
    if(empty($username_err) && empty($password_err) && empty($email_err) && empty($telefoon_err) && empty($datum_err) && empty($geslacht_err)){
        // bereid een insert statement voor
        $sql = "INSERT INTO account (username, password, email, telefoon, datum, geslacht) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables aan de prepared statement als parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_email, $param_telefoon, $param_datum, $param_geslacht);
            
            // zet parameters
            $param_username = $username;
            $param_password = $password;
            $param_email = $email;
            $param_telefoon = $telefoon;
            $param_datum = $datum;
            $param_geslacht = $geslacht;
            
            // probeer prepared statement te runnen
            if(mysqli_stmt_execute($stmt)){
                // succesvol redirect
                header("location: index.php");
                exit();
            } else{
                echo "Oops er is iets fout gegaan, probeer opnieuw.";
            }
        }
         
        // sluit statement
        mysqli_stmt_close($stmt);
    }
    
    // sluit connectie
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Schrijf je hier in om mee te gaan naar de finale.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <label>Naam,Adres</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>Wachtwoord</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block"><?php echo $password_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <label>E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($telefoon_err)) ? 'has-error' : ''; ?>">
                            <label>Telefoon Nummer</label>
                            <input type="text" name="telefoon" class="form-control" value="<?php echo $telefoon; ?>">
                            <span class="help-block"><?php echo $telefoon_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($datum_err)) ? 'has-error' : ''; ?>">
                            <label>Geboorte Datum</label>
                            <input type="text" name="datum" class="form-control" value="<?php echo $datum; ?>">
                            <span class="help-block"><?php echo $datum_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($geslacht_err)) ? 'has-error' : ''; ?>">
                            <label>Geslacht m/v/anders</label>
                            <input type="text" name="geslacht" class="form-control" value="<?php echo $geslacht; ?>">
                            <span class="help-block"><?php echo $geslacht_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>