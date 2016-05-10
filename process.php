<?php
require_once('functions.php');

// To read all new line versions, including \r.
ini_set("auto_detect_line_endings", true);

if( isset($_POST) ) {
    //form validation vars
    $formok = true;
    $errors = array();
    $successes = array();
    
    //submission data
    $date = date('Y-m-d');
    $time = date('H-i-s');
    $uploads_dir = __DIR__ . '/files/uploads/';
    $write_filename = 'customer-match-' . $date . '-' . $time . '.csv';
    
    //form data
    $tmp_file = $_FILES['file']['tmp_name'];
    $file = $_FILES['file']['name'];
    
    //validate name is not empty
    if(empty($tmp_file)){
        $formok = false;
        $errors[] = "You have not selected any file";
    }
    
    $result = copy($tmp_file, $uploads_dir . $file);
    
    if(FALSE === $result) {
        $errors[] = "Error copying uploaded file.";
    } else {
        $successes[] = "Success copying uploaded file.";
    }

    if(($readfp = fopen($uploads_dir . $file, 'r')) !== false) {
        // Get the first row, which contains the column-titles.
        $header = fgetcsv($readfp);
        
        if(FALSE === $header) {
            $errors[] = "Error reading first line of input file.";
        } else {
            $successes[] = "Success reading first line of input file.".json_encode($header);
        }
        
        $writefp = fopen('files/' . $write_filename, 'w');
        
        if(FALSE === $writefp) {
            $errors[] = "Error opening output file.";
        } else {
            $successes[] = "Success opening output file.";
        }
        
        // Loop through the file line-by-line
        $i = 0;
        while(($data = fgetcsv($readfp)) !== false) {
            $i++;
            if(FALSE === $data) {
                $errors[] = "Error reading line of input file with fgetcsv().";
            } else {
                $successes[] = "Success writing first line to output file.";
            }
            
            $sha256_data = hash('sha256', trim($data[0]));
            $result = fwrite($writefp, $sha256_data . PHP_EOL);
            
            if(FALSE === $result) {
                $errors[] = "Error writing '" . $sha256_data . "' to output file.";
            } else {
                $successes[] = "Success writing '" . $sha256_data . "' to output file, line " . $i . ".";
            }
            
            unset($data);
        }
        
        fclose($writefp);
        fclose($readfp);
    } else {
        $errors[] = "Error opening input file '" . $file . "'.";
        foreach(error_get_last() as $last_error) {
            $errors[] = $last_error;
        }
    }
    
    //what we need to return back to our form
    $returndata = array(
        'posted_form_data' => array(
            'file' => $file,
            'write_filename' => $write_filename
        ),
        
        'form_ok' => $formok,
        'errors' => $errors,
        '_POST' => $_POST,
        '_FILES' => $_FILES,
        //'successes' => $successes
    );
    
    //if this is not an ajax request
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        //set session variables
        session_start();
        $_SESSION['cf_returndata'] = $returndata;
         
        //redirect back to form
        header('location: ' . $_SERVER['HTTP_REFERER']);
    }
}
