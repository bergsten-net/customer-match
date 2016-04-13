<?php
// From http://code.tutsplus.com/tutorials/build-a-neat-html5-powered-contact-form--net-20426
if( isset($_POST) ){
    //form validation vars
    $formok = true;
    $errors = array();
     
    //submission data
    $date = date('Y-m-d');
    $time = date('H-i-s');
    $write_filename = 'files/customer-match-' . $date . '-' . $time . '.csv';
    
    //form data
    $file = $_POST['file'];

    //validate name is not empty
    if(empty($file)){
        $formok = false;
        $errors[] = "You have not selected any file";
    }
    
    if(($readfp = fopen($file, 'r')) !== false) {
        // Get the first row, which contains the column-titles (if necessary)
        $header = fgetcsv($readfp);
        
        $writefp = fopen($write_filename, 'w');
        
        // loop through the file line-by-line
        while(($data = fgetcsv($readfp)) !== false) {
            $sha256_data = hash('sha256', trim($data[0]));
            fwrite($writefp, $sha256_data . PHP_EOL);
            
            // resort/rewrite data and insert into DB here
            // try to use conditions sparingly here, as those will cause slow-performance

            // I don't know if this is really necessary, but it couldn't harm;
            // see also: http://php.net/manual/en/features.gc.php
            unset($data);
        }
        
        fclose($writefp);
        fclose($readfp);
    }
    
    //what we need to return back to our form
    $returndata = array(
        'posted_form_data' => array(
            'file' => $file,
            'write_filename' => $write_filename
        ),
        'form_ok' => $formok,
        'errors' => $errors
    );
    
    //if this is not an ajax request
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){
        //set session variables
        session_start();
        $_SESSION['cf_returndata'] = $returndata;
         
        //redirect back to form
        header('location: ' . $_SERVER['HTTP_REFERER']);
    }
}

