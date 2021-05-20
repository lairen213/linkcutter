<?php
require_once 'server.php';

//проверяем, обычная ссылка или сокращённая
if (isset($_GET['c'])) {
    //Проверяем есть ли такая ссылка в бд

    $link = "http://linkcutter";//ВАШ ДОМЕН, важно чтобы была без слеша в конце
    $redirectLink = checkRedirect($link."/?c=".$_GET['c']);

    if($redirectLink){//Если есть то перенаправляем на оригинальную ссылку
        
        header('Location: '.$redirectLink,true, 301);
    }else{
        echo '<script type="text/javascript">alert("Ссылка не найдена...");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link cutter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <form class="row col-12" id="cutForm">
            <div class="form-group">
                <textarea class="form-control" id="staticLink" placeholder="Ссылка для сокращения" rows="3"></textarea>
            </div>
            <div class="col-auto mt-2">
                <button type="submit" class="btn btn-primary mb-3">Сократить ссылку</button>
            </div>
        </form>

        <form class="row mt-2 col-12" id="loginForm">
            <div class="form-group">
                <input type="email" class="form-control" id="staticEmail" placeholder="Email" aria-describedby="emailHelp"></input>
            </div>
            <div class="col-auto mt-2">
                <button type="submit" class="btn btn-primary mb-3">Войти</button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Ссылка успешно сокращена!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="row" id="emailForm">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Желаете отправить на почту?</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="col-auto mt-3">
                        <button type="submit" class="btn btn-primary mb-3">Отправить</button>
                    </div>
                </form>

                <div class="col-auto">
                    <input type="text" readonly class="form-control" id="staticCutLink" value="">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Вход</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="row" id="loginConfForm">
                    <div class="form-group">
                        <label for="staticCode">Введите код подтверждения, который был отправлен вам на почту:</label>
                        <input type="text" class="form-control" id="staticCode" placeholder="Код"></input>
                    </div>
                    <div class="col-auto mt-3">
                        <button type="submit" class="btn btn-primary mb-3">Отправить</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button type="button" id="btnModal" class="btn btn-primary d-none" data-toggle="modal" data-target="#emailModal">
        Launch demo modal
    </button>

    <!-- Button trigger modal -->
    <button type="button" id="btnModal2" class="btn btn-primary d-none" data-toggle="modal" data-target="#loginModal">
        Launch demo modal
    </button>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#emailForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'server.php',
                    data: { email: $("#exampleInputEmail1").val(), cutlink: $("#staticCutLink").val()},
                    success: function(data){
                        let emailll = data;
                        alert(emailll);
                    }
                });
            });
            
            $('#loginConfForm').submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'server.php',
                    data: { email: $("#staticEmail").val(), code: $("#staticCode").val()},
                    success: function(data){
                        if(data == "Succes"){
                            alert("Код верный!");
                            var url = "/login.php?email="+$("#staticEmail").val();
                            $(location).attr('href',url);
                        }else{
                            alert("Неверный код!");
                        }
                    }
                });
            });

            $('#loginForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'server.php',
                    data: { email: $("#staticEmail").val(), login: 1},
                    success: function(data){
                        document.querySelector('#btnModal2').click();
                    }
                });
            });

            $('#cutForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'server.php',
                    data: {link:$("#staticLink").val()},
                    success: function(data){
                        let cutedLink = data;
                        if(cutedLink != "" && cutedLink != "not successful"){
                            $("#staticCutLink").val(cutedLink);
                            document.querySelector('#btnModal').click();
                        }else if(cutedLink == ""){
                            alert("Заполните поле c ссылка!");
                        }else{
                            alert("Упс.. Что-то произошло не так.\nУбедитесь что вы вставили ссылку");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>