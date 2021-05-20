<?php
//БД
require_once 'db.php';
$host = $host2; // адрес сервера 
$database = $database2; // имя базы данных
$user = $user2; // имя пользователя
$password = $password2; // пароль

$email = "none";
$countS = 0;
//Подключение бд
$link = mysqli_connect($host, $user,$password,$database) 
or die("Ошибка " . mysqli_error($link));

if (isset($_GET['email'])) {
    $email = $_GET['email'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loged in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
    <?php

        echo '<table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Ссылка</th>
                    <th scope="col">Количетсов переходов</th>
                </tr>
            </thead>
            <tbody>';
            global $link;

            $query ="SELECT * FROM link WHERE email='$email'";
            $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
            if($result)
            {
                while($row = mysqli_fetch_array($result)){
                    $cuttedLink = $row["cuttedLink"];
                    echo '
                    <tr>
                        <td><a href="'.$cuttedLink.'">'.$cuttedLink.'</a></td>
                        <th scope="row">'.$row["countSaw"].'</th>
                    </tr>';
                }
            }
                
                
            echo '</tbody>
        </table>';

    ?>
    </div>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>