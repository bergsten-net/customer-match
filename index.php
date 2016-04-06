<?php
session_start();
require_once('./templates/header.php');
?>
                <article>
                    <header>
                        <h1>Customer Match</h1>
                        <div id="contact-form" class="clearfix">
                            <h2>Ange CSV-fil att ladda upp</h2>
                            <ul id="errors" class="">
                                <li id="info">Det blev något fel:</li>
                            </ul>
                            <p id="success">Det funkade!</p>
                            <?php
                            //init variables
                            $cf = array();
                            $sr = false;

                            if(isset($_SESSION['cf_returndata'])){
                                $cf = $_SESSION['cf_returndata'];
                                $sr = true;
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
                            <form method="post" action="process.php">
                                <label for="name">File: <span class="required">*</span></label>
                                <input type="file" id="file" name="file" required="required" />
                                <span id="loading"></span>
                                <input type="submit" value="Ladda upp &raquo;" id="submit-button" />
                                <p id="req-field-desc"><span class="required">*</span> indicates a required field</p>
                            </form>
                        </div>
                    </section>
                </article>
<?php
//require_once('./templates/aside.php');
require_once('./templates/footer.php');
?>