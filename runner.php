<?php
function SendMail($email)
{   
    require('Email.php');
    $response = file_get_contents('https://c.xkcd.com/random/comic');

    $dom = new DOMDocument;


    @$dom->loadHTML($response);


    $metas = $dom->getElementsByTagName('meta');


    foreach ($metas as $meta) {


    if (@$meta->getAttribute('property') == 'og:url') {
        $init_url = $meta->getAttribute('content');
        if (isset($title)) {
            break;
        }
    }
    if (@$meta->getAttribute('property') == 'og:title') {
        $title = $meta->getAttribute('content');
        if (isset($init_url)) {
            break;
        }
    }
    }
    $str = parse_url($init_url)['path'];
    $count = 2;
    $p = str_replace('/', '', $str, $count);
    $p = 'https://xkcd.com/' . $p . '/info.0.json';

    $response = file_get_contents($p);

    $json = json_decode($response);

    //$image = '<img src=\'' . $json->img . '\' alt = "image">';

    $body = '
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>comic</title>
        <style>
            .container {
                display: flex;
                justify-content: center;
            }

            .heading {
                font-size: 2em;
                font-family: "Gill Sans", "Gill Sans MT", "Calibri", "Trebuchet MS", "sans-serif";
            }
            a{
                display : block;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="heading">
            Todays comic :  ' . $title . '
            </div>
        </div>
        <br><br>
        <div class = "container">
        <img src = "cid:inline_image">
        </div>
        <br><br>

        <div class="container">
        <a href="https://intern-app-php.herokuapp.com/unsubscribe.php" alt = "image">Unsubscribe</a>
        </div>
        
    </body>';


    $url = $json->img;


    // Function to write image into file
    #file_put_contents($img, file_get_contents($url));
    $file = base64_encode(file_get_contents($url));
    mailer("Comic!", $email, " ", $body, " ", $file);
    }

    if (isset($_POST['run_script']) && !function_exists('mailer')) {

    require('database.php');

    $query = 'select * from table';

    $result = $database->query($query);

    if ($result->num_rows > 0) {

    $now = new DateTime();

    while ($row = $result->fetch_assoc()) {
    $getDate = new DateTime($row['time_snap']);
    if (date_diff($getDate, $now)->i >= 5) {
    
    $email = $row['email'];
    SendMail($email);
    $date = date('Y-m-d H:i:s');
    $query = 'update table set time_snap =\''.$date.'\' where email = \''.$email.'\'';
    $database->query($query);
    }
    }
    }
    $database->closeDatabase();
    }
    
?>