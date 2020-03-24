<?php
//Задание 2
function transformUrl ($url) {
    $urlArray = parse_url($url);
    echo "<br>";
    $params = [];
    foreach(explode('&', $urlArray[query]) as $item) {
        $param = explode("=", $item);
        if ($param && $param[1] != 3) {
            $key = urldecode($param[0]);
            $value = urldecode($param[1]);
            $params[$key] = $value;
        }
    }
    asort($params);
    $params['url'] = $urlArray[path];
    $newUrl = $urlArray[scheme].'://'.$urlArray[host].'/?'.http_build_query($params, '', '&amp;');
    return $newUrl;
}

$url = 'https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3';
echo(transformUrl($url));

//Задание 4
//уязвимость - через гет можно передать передать любую строку 1,2,1;DROP TABLE users;
//отдельные запросы для каждого пользователя для каждого запроса новое открытие бызы
//функции необходимо передавать массив вместо строки
function load_users_data($user_ids) {
    //$user_ids = explode(',', $user_ids);//убрать
    //foreach ($user_ids as $user_id) {//проверка isnumber, implode() в строку и строку в запрос
        $db = mysqli_connect("localhost", "root", "123123", "database");
        $sql = mysqli_query($db, "SELECT * FROM users WHERE id=$user_id");
        while($obj = $sql->fetch_object()){//
            $data[$user_id] = $obj->name;
        }
        mysqli_close($db);
    }
    return $data;
}
// Как правило, в $_GET['user_ids'] должна приходить строка
// с номерами пользователей через запятую, например: 1,2,17,48
$data = load_users_data($_GET['user_ids']);
foreach ($data as $user_id=>$name) {
    echo "<a href=\"/show_user.php?id=$user_id\">$name</a>";
}

//Задание 3
