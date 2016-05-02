<?php 
function pr($array, $title = 'Array', $output = 'local') {
    $client_ip = $_SERVER['REMOTE_ADDR'];
    $server_ip = $_SERVER['SERVER_ADDR'];
    
    if($output != 'local' || strcmp($server_ip, $client_ip) === 0) {
        echo($title . ':<pre>');
        print_r($array);
        echo('</pre>');
    }
}
