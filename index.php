<?php
//Задание 1
/*
SELECT users_test.name, COUNT(phone_numbers.phone)
FROM users_test
LEFT JOIN phone_numbers ON users_test.id = phone_numbers.user_id
WHERE gender = 2 AND (UNIX_TIMESTAMP(NOW()) - birth_date) / 31556926 BETWEEN 18 AND 22
GROUP BY users_test.name
*/

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
echo "<pre>";
echo(transformUrl($url));
echo "</pre>";

//Задание 3
/*
class ArticleService implements IArticleService {.......}
interface IArticleService
{
    function getByUserId($authorId); // @returns array of type Article

    function changeAuthor($articleId, $userId); // void

    function add($userId, $article); // void
}
interface IArticleService
{
    function getByUserId($authorId); // @returns array of type Article

    function changeAuthor($articleId, $userId); // void

    function add($userId, $article); // void
}

interface IUserService
{
    function getById($userId); // @returns User
}
 */

//Задание 4
//уязвимость - через гет можно передать передать любую строку в том числе инъекцию
//отдельные запросы для каждого пользователя для каждого запроса новое открытие бызы
//функции необходимо передавать массив вместо строки
/*
function load_users_data($user_ids) {
    $user_ids = explode(',', $user_ids);//убрать
    foreach ($user_ids as $user_id) {//проверка isnumber, implode() в строку и строку в запрос
        $db = mysqli_connect("localhost", "root", "123123", "database");
        $sql = mysqli_query($db, "SELECT * FROM users WHERE id=$user_id");
        while($obj = $sql->fetch_object()){//
            $data[$user_id] = $obj->name;
        }
        mysqli_close($db);
    }
    return $data;
}
*/
function load_users_data ($userIds) {
    /*foreach($userIds as $userId) {
        if(!is_numeric($userId)) return "Массив содержит нечисловое значение";
    }*/
    $userIdsStr = implode(',', $userIds);
    echo "$"
    $db = mysqli_connect("localhost", "root", "", "mybase");
    $sql = mysqli_query($db, "SELECT * FROM users_test WHERE id in {$userIdsStr}");
    echo ($sql);
    /*while($obj = $sql->fetch_object()) {
        $data[$obj->id] = $obj->name;
    }*/
    mysqli_close($db);
    return $data;
}
// Как правило, в $_GET['user_ids'] должна приходить строка
// с номерами пользователей через запятую, например: 1,2,17,48
$data = load_users_data([1, 2, 3]);//$_GET['user_ids']);
foreach ($data as $user_id=>$name) {
    echo "<a href=\"/show_user.php?id=$user_id\">$name</a>";
}

