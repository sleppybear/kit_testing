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
    return $urlArray[scheme].'://'.$urlArray[host].'/?'.http_build_query($params, '', '&amp;');
}

$url = 'https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3';
echo "<pre>";
echo(transformUrl($url));
echo "</pre>";

//Задание 3

/**
 * Класс "пользователь"
 * Class User
 */
class User
{
    /**
     * Атрибут хранящий id пользователя
     * @var integer
     */
    public $userId;
    /**
     * Атрибут хранящий имя пользователя
     * @var string
     */
    public $username;
}

/**
 * Класс "Статья"
 * Class Article
 */
class Article
{
    /**
     * @var integer
     */
    private $authorId;
    /**
     * @var string
     */
    public $content;

    /**
     * Получить автора статьи
     * @return int
     */
    public function GetAuthorId()
    {
        return $this->authorId;
    }
}

/**
 * Interface IArticleService
 */
interface IArticleService
{
    /**
     * Получить все статьи пользователя
     * @param $authorId
     * @return array of type Article
     */
    function getByUserId($authorId);

    /**
     * Изменить автора статьи
     * @param $articleId
     * @param $userId
     * @return void
     */
    function changeAuthor($articleId, $userId);

    /**
     * Добавить статью автора
     * @param $userId
     * @param $article
     * @return void
     */
    function add($userId, $article);
}

/**
 * Interface IUserService
 */
interface IUserService
{
    /**
     * @param $userId
     * @return User
     */
    function getById($userId);
}

//Задание 4
//уязвимость - через get можно передать передать любую строку в том числе инъекцию
//отдельные запросы и подключения для каждого пользователя
//на сервер должен приходить массив вместо строки для безопасности
/*
function load_users_data($user_ids) { //вместо строки передавать массив
    $user_ids = explode(',', $user_ids); //проверить, что в массиве только int
    foreach ($user_ids as $user_id) {
        //необходимо убрать подключение из цикла и изменить запрос
        $db = mysqli_connect("localhost", "root", "123123", "database");
        $sql = mysqli_query($db, "SELECT * FROM users WHERE id=$user_id");
        while($obj = $sql->fetch_object()){ //
            $data[$user_id] = $obj->name;
        }
        mysqli_close($db);
    }
    return $data;
}
*/
function load_users_data ($userIds) { //передаётся массив int
    foreach($userIds as $userId) {
        //проверка что в массиве только int
        if(!is_int($userId)) return false;
    }
    //объединение всех id в строку
    $userIdsStr = implode(',', $userIds);
    $db = mysqli_connect("localhost", "root", "", "mybase");
    //запрос сразу всех записей без цикла
    $sql = mysqli_query($db, "SELECT `id`, `name` FROM users_test 
                                WHERE `id` in ({$userIdsStr})");
    if($sql) {
        while ($row = mysqli_fetch_assoc($sql)) {
            $data[$row["id"]] = $row["name"];
        }
    }
    mysqli_close($db);
    return $data;
}
// Как правило, в $_GET['user_ids'] должна приходить строка
// с номерами пользователей через запятую, например: 1,2,17,48
$data = load_users_data([1, 2, 3]);//$_GET['user_ids']);
//запрос необходим POST а не GET
foreach ($data as $user_id => $name) {
    echo "<a href=\"/show_user.php?id=$user_id\">$name</a><br>";
}

