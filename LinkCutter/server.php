<?php
    //БД
    require_once 'db.php';

    $host = $host2; // адрес сервера 
    $database = $database2; // имя базы данных
    $user = $user2; // имя пользователя
    $password = $password2; // пароль

    //Подключение бд
    $link = mysqli_connect($host, $user,$password,$database) 
    or die("Ошибка " . mysqli_error($link));

    if (isset($_POST['link'])) {
        addCuttedLink($_POST['link']);
    }

    if(isset($_POST['login'])){
        loginByEmail($_POST['email']);
    }
    else if(isset($_POST['code'])){
        checkCode($_POST['email'], $_POST['code']);
    }
    else if(isset($_POST['email'])){
        addEmail($_POST['email'], $_POST['cutlink']);
    }

    function checkCode($email, $code){
        // подключаемся к серверу
        global $host;
        global $user;
        global $password;
        global $database;
        $link = mysqli_connect($host,$user,$password,$database) 
        or die("Ошибка " . mysqli_error($link));

        // создание строки запроса
        $query ="SELECT * FROM confirmationcode";
        
        // выполняем запрос
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
        if($result)
        {
            while($row = mysqli_fetch_array($result)){
                $email2 = $row["email"];
                $code2 = $row["code"];
                
                if($code2 == $code && $email2 == $email){//если такая строка уже существует
                    echo "Succes";
                    deleteCode($email);
                    return true;
                }
            }
        }
        echo "Bad";
        return false;
    }

    function deleteCode($email){
        // подключаемся к серверу
        global $host;
        global $user;
        global $password;
        global $database;
        $link = mysqli_connect($host,$user,$password,$database) 
        or die("Ошибка " . mysqli_error($link));

        // создание строки запроса
        $query ="DELETE FROM confirmationcode WHERE email = '$email'";
        
        // выполняем запрос
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
    }

    function addCodeToBd($email, $code){
        // подключаемся к серверу
        global $host;
        global $user;
        global $password;
        global $database;
        $link = mysqli_connect($host,$user,$password,$database) 
        or die("Ошибка " . mysqli_error($link));
        
        $em = htmlentities(mysqli_real_escape_string($link, $email));
        $co = htmlentities(mysqli_real_escape_string($link, $code));

        // создание строки запроса
        $query ="INSERT INTO confirmationcode (email, code) VALUES('$em','$co')";
        
        // выполняем запрос
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
    }

    function loginByEmail($email){
        $code = random_string(6);
        $retval = mail($email, "Confirmation code", "Your code:\n".$code);
        if($retval == true ) {
            addCodeToBd($email, $code);
            return true;
        }else {
            return false;
        }
    }

    function addEmail($email, $cutedLink){//Отправка ссылки на почту
        $retval = mail($email, "Cutted link", "Your link:\n".$cutedLink);
        if($retval == true ) {
            echo "Сообщение отправлено успешно...";
        }else {
            echo "Ошибка отправки сообщения...";
        }
        updateEmail($email, $cutedLink);

    }

    function updateEmail($email, $cutedLink){//Добавляем почту к ссылке которую сократили
        global $host;
        global $user;
        global $password;
        global $database;
        $link = mysqli_connect($host,$user,$password,$database) 
        or die("Ошибка " . mysqli_error($link));

        $query ="UPDATE link SET email='$email' WHERE cuttedLink='$cutedLink'";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }

    function checkRedirect($cutlink){//Редирект сокращённой ссылки
        global $host;
        global $user;
        global $password;
        global $database;
        $link = mysqli_connect($host,$user,$password,$database) 
        or die("Ошибка " . mysqli_error($link));

        $query ="SELECT * FROM link";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
        if($result)
        {
            while($row = mysqli_fetch_array($result)){
                $cuttedLink = $row["cuttedLink"];
                if($cuttedLink == $cutlink){//если такая строка уже существует
                    updateCountSaw($cutlink, $row["countSaw"]+1);
                    return $row["originalLink"];
                }
            }
        }

        return false;
    }

    function updateCountSaw($cutlink, $cou){
        global $host;
        global $user;
        global $password;
        global $database;
        $link = mysqli_connect($host,$user,$password,$database) 
        or die("Ошибка " . mysqli_error($link));

        $query ="UPDATE link SET countSaw='$cou' WHERE cuttedLink='$cutlink'";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }

    function addCuttedLink($orLink){//Добавление сокращенную строку вместе с проверкой
        global $link;
        global $majorLink;
        $check = 0;
        $shifr = random_string(5);
        $newlink = $majorLink."/?c=$shifr";
        do{
            if(checkCuttedLink($newlink)){
                $check = 1;
            }
            $shifr = random_string(5);
            $newlink = $majorLink."/?c=$shifr";
        }while($check == 0);

        addNewCuttedLink($orLink, $newlink);
    }
    
    function addNewCuttedLink($orLink, $cuttedL){//Добавляет новую сокращенную строку в бд
        // подключаемся к серверу
        global $link;
        
        $ol = htmlentities(mysqli_real_escape_string($link, $orLink));
        $cl = htmlentities(mysqli_real_escape_string($link, $cuttedL));

        // создание строки запроса
        $query ="INSERT INTO link (originalLink, cuttedLink, countSaw) VALUES('$ol','$cl', 0)";
        
        // выполняем запрос
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
        if($result)
        {
            echo $cuttedL;
        }
        // закрываем подключение
        //mysqli_close($link);
    }

    function checkCuttedLink($cutlink){//Проверка на то, создавалась ли уже такая ссылка
        global $link;

        $query ="SELECT * FROM link";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
        if($result)
        {
            while($row = mysqli_fetch_array($result)){
                $cuttedLink = $row["cuttedLink"];
                if($cuttedLink == $cutlink){//если такая строка уже существует
                    return false;
                }
            }
        }

        return true;
    }
    
    // Создаёт рандомною строку
    function random_string ($str_length)
    {
        $str_characters = array (0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        // Возвращаем ложь, если первый параметр равен нулю или не является целым числом
        if (!is_int($str_length) || $str_length < 0)
        {
            return false;
        }

        // Подсчитываем реальное количество символов, участвующих в формировании случайной строки и вычитаем 1
        $characters_length = count($str_characters) - 1;

        // Объявляем переменную для хранения итогового результата
        $string = '';

        // Формируем случайную строку в цикле
        for ($i = $str_length; $i > 0; $i--)
        {
            $string .= $str_characters[mt_rand(0, $characters_length)];
        }

        // Возвращаем результат
        return $string;
    }
   
?>