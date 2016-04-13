<?php 
$client_ip = $_SERVER['REMOTE_ADDR'];
$server_ip = $_SERVER['SERVER_ADDR'];

function pr($array, $title = 'Array', $output = 'local') {
    global $client_ip, $server_ip;
    
    if($output != 'local' || strcmp($server_ip, $client_ip) === 0) {
        echo($title . ':<pre>');
        print_r($array);
        echo('</pre>');
    }
}
