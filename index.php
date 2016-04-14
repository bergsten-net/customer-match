<?php
session_start();
require_once('functions.php');
require_once('./templates/header.php');
?>
                <article>
                        <div id="contact-form" class="clearfix">
                            <h2>Ange CSV-fil att ladda upp</h2>
                            <p>Första raden i filen kommer tas bort, så se till att första raden inte innehåller en e-postadress.</p>
                            <ul id="errors" class="">
                                <li id="info">Det blev något fel:</li>
                            </ul>
                            <p id="success">Det funkade!</p>
                            <?php
                            //init variables
                            $cf = array();
                            $sr = false;
//echo('client_ip: ' . $client_ip . ', server_ip: ' . $server_ip . '<br />');
pr($_SESSION, '_SESSION');
                            if(isset($_SESSION['cf_returndata'])){
                                $cf = $_SESSION['cf_returndata'];
                                $sr = true;
                                
                                if(0 < count($_SESSION['cf_returndata']['errors'])) { ?>
                            <h2>Fel:</h2>
                            <ul><?php
                                    foreach($_SESSION['cf_returndata']['errors'] as $error) {
                                        echo('<li>' . $error . '</li>');
                                    } ?>
                            </ul><?php
                                } else {
                                    $write_filename = $_SESSION['cf_returndata']['posted_form_data']['write_filename'];
                                    echo('<a href="files/' . $write_filename . '">Ladda ner filen ' . $write_filename . '</a><br />');
                                }
                            } ?>
                            <ul id="errors" class="<?php echo ($sr && !$cf['form_ok']) ? 'visible' : ''; ?>">
                                <li id="info">Det blev något fel:</li>
                                <?php 
                                if(isset($cf['errors']) && count($cf['errors']) > 0) :
                                    foreach($cf['errors'] as $error) :
                                ?>
                                <li><?php echo $error ?></li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                            <form method="post" action="process.php" enctype="multipart/form-data">
                                <label for="name">Fil: <span class="required">*</span></label>
                                <input type="file" id="file" name="file" required="required" />
                                <span id="loading"></span>
                                <input type="submit" value="Ladda upp &raquo;" id="submit-button" />
                                <p id="req-field-desc"><span class="required">*</span> obligatoriskt fält</p>
                            </form>
                        </div>
                    </section>
                </article>
<?php
//require_once('./templates/aside.php');
require_once('./templates/footer.php');
