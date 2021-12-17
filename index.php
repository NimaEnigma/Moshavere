<?php
$question = "";
$msg = "سوال خود را بپرس!";
$names=array();
$messages_array=array();
$messages_file= fopen("messages.txt", "r");
$people = json_decode(file_get_contents('people.json'));
$i=1;
foreach ($people as $key => $value) {
    $names[$i] = $key;
    $i++;}
$j=1;
while (!feof($messages_file)) {
    $messages_array[$j] = fgets($messages_file);
    $j++;}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $en_name = $_POST["person"];
    $question = $_POST["question"];
    $msg_key = (hexdec(hash('adler32', $question . " " . $en_name))%16);
    $msg = $messages_array[$msg_key];
    foreach ($people as $key => $value) {
        if ($key == $en_name) {
            $fa_name = $value;
            }
        }
} else {
    $random = array_rand($names);
    $en_name = $names[$random];
    foreach ($people as $key => $value) {
        if ($key == $en_name) {
            $fa_name = $value;
            }
        }
    }
$aya = "/^آیا/iu";
$english_qm = "/\?$/i";
$persian_qm = "/؟$/u";
if(! preg_match($aya , $question) ) 
{
    $msg = "سوال درستی پرسیده نشده";
}
if(!(preg_match($persian_qm , $question) || preg_match($english_qm , $question)))
{
    $msg = "سوال درستی پرسیده نشده";   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label"><?php
        if ($question != "") {
                    echo "پرسش:";
                }?>
        </span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p>
            <?php
                if ($question == "") {
                    echo "سوال خود را بپرس!";
                } else
                    echo $msg;
            ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <?php
                foreach ($people as $key => $value) {
                    if ($en_name == $key) {
                        echo "<option value=$key selected> $value</option> ";
                    } else {
                        echo "<option value=$key > $value</option> ";
                    }
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>