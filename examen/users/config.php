<?php
/* database gegevens */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', '84222');
define('DB_PASSWORD', 'Hamster2003');
define('DB_NAME', 'Examen');
 
/* Proberen met de database te connecten */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// kijk of de conncectie is gemaakt
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>