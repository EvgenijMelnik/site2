<?php
//подключение СУБД MySQL
function connect(
    $host = 'localhost',
    $user = 'root',
    $pass = 'password',
    $db = 'travels'
) {
    $mysqli = mysqli_connect($host, $user, $pass, $db)
        or die('connection error');
    mysqli_query($mysqli, "set names 'utf8'");
    return $mysqli;
}

//Регистрация пользователей
function register($name, $pass, $email)
{
    //обезвреживаем данные от пользователя
    $name = trim(htmlspecialchars($name));
    $pass = trim(htmlspecialchars($pass));
    $email = trim(htmlspecialchars($email));
    //Валидация на пустые значения
    if ($name == "" || $pass == "" || $email == "") {
        echo "<h3/><span style='color:red;'>
    Fill All Required Fields!
    </span><h3/>";
        return false;
    }
    //Валидация на длинну полей
    if (
        strlen($name) < 3 || strlen($name) > 30 ||
        strlen($pass) < 3 ||
        strlen($pass) > 30
    ) {
        echo "<h3/><span style='color:red;'>
    Values Length Must Be Between 3
    And 30!
    </span><h3/>";
        return false;
    }
    $ins = 'insert into users
    (login,pass,email,roleid)
    values("' . $name . '","' .
        md5($pass) . '","' . $email . '",2)';
    $mysqli = connect();
    mysqli_query($mysqli, $ins);
    $err = mysqli_errno($mysqli);
    if ($err) {
        if ($err == 1062)
            echo "<h3/><span style='color:red;'>
    This Login Is Already Taken!
    </span><h3/>";
        else
            echo "<h3/><span style='color:red;'>
    Error code:" . $err . "!</
    span><h3/>";
        return false;
    }
    return true;
}
//авторизация пользователя по логину и паролю
function login($name, $pass)
{
    $name = trim(htmlspecialchars($name));
    $pass = trim(htmlspecialchars($pass));
    if ($name == "" || $pass == "") {
        echo "<h3/><span style='color:red;'>
Fill All Required Fields!</span><h3/>";
        return false;
    }
    if (
        strlen($name) < 3 || strlen($name) > 30 ||
        strlen($pass) < 3 || strlen($pass) > 30
    ) {
        echo "<h3/><span style='color:red;'>
Value Length Must Be Between 3 And 30!
</span><h3/>";
        return false;
    }
    $mysqli = connect();
    $sel = 'select * from users 
    where login="' . $name . '"
and pass="' . md5($pass) . '"';
    $res = mysqli_query($mysqli,$sel);
    if ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
        $_SESSION['ruser'] = $name;
        if ($row[5] == 1) {
            $_SESSION['radmin'] = $name;
        }
        return true;
    } else {
        echo "<h3/><span style='color:red;'>
No Such User!</span><h3/>";
        return false;
    }
}
