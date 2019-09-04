<?php
$driver = 'mysql';
$database = "dbname=CODINGGROUND";
$dsn = "$driver:host=localhost;$database";

$username = 'root';
$password = 'root';

try {
   $conn = new PDO($dsn, $username, $password);
   echo "Database CODINGGROUND Connected\n";
}catch(PDOException $e){
   echo $e->getMessage();
}
$sql = 'SELECT  * FROM users';
$stmt = $conn->prepare($sql);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  foreach($row as $value)
  {
    echo sprintf("%s, ", $value);
  }
  echo "\n";

if (!mysql_connect(DB_HOST, DB_USER, DB_PASS)) {
    exit('Cannot connect to server');
}
if (!mysql_select_db(DB_NAME)) {
    exit('Cannot select database');
}

mysql_query('SET NAMES utf8');

function search ($query) 
{ 
    $query = trim($query); 
    $query = mysql_real_escape_string($query);
    $query = htmlspecialchars($query);

    if (!empty($query)) 
    { 
        if (strlen($query) < 3) {
            $text = '<p>Слишком короткий поисковый запрос.</p>';
        } else if (strlen($query) > 128) {
            $text = '<p>Слишком длинный поисковый запрос.</p>';
        } else { 
            $q = "SELECT `page_id`, `title`, `desc`, `title_link`, `category`, `uniq_id`
                  FROM `table_name` WHERE `text` LIKE '%$query%'
                  OR `title` LIKE '%$query%' OR `meta_k` LIKE '%$query%'
                  OR `meta_d` LIKE '%$query%'";

            $result = mysql_query($q);

            if (mysql_affected_rows() > 0) { 
                $row = mysql_fetch_assoc($result); 
                $num = mysql_num_rows($result);

                $text = '<p>По запросу <b>'.$query.'</b> найдено совпадений: '.$num.'</p>';

                do {
                    // Делаем запрос, получающий ссылки на статьи
                    $q1 = "SELECT `link` FROM `table_name` WHERE `uniq_id` = '$row[page_id]'";
                    $result1 = mysql_query($q1);

                    if (mysql_affected_rows() > 0) {
                        $row1 = mysql_fetch_assoc($result1);
                    }

                    $text .= '<p><a> href="'.$row1['link'].'/'.$row['category'].'/'.$row['uniq_id'].'" title="'.$row['title_link'].'">'.$row['title'].'</a></p>
                    <p>'.$row['desc'].'</p>';

                } while ($row = mysql_fetch_assoc($result)); 
            } else {
                $text = '<p>По вашему запросу ничего не найдено.</p>';
            }
        } 
    } else {
        $text = '<p>Задан пустой поисковый запрос.</p>';
    }

    return $text; 
} 
?>

<?php 
if (!empty($_POST['query'])) { 
    $search_result = search ($_POST['query']); 
    echo $search_result; 
}
?>