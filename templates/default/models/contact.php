<?php
$msg_error = '';
$msg_success = '';
$field_notice = array();

if(isset($_POST['send'])){
    
    if(PMS_CAPTCHA_PKEY != '' && PMS_CAPTCHA_SKEY != ''){
        require(SYSBASE.'includes/recaptchalib.php');
    
        $secret = PMS_CAPTCHA_SKEY;
        $response = null;
        $reCaptcha = new ReCaptcha($secret);
        if(isset($_POST['g-recaptcha-response']))
            $response = $reCaptcha->verifyResponse($_SERVER['REMOTE_ADDR'], $_POST['g-recaptcha-response']);
        if($response == null || !$response->success) $field_notice['captcha'] = $pms_texts['INVALID_CAPTCHA_CODE'];
    }
    
    $name = html_entity_decode($_POST['name'], ENT_QUOTES, 'UTF-8');
    $address = html_entity_decode($_POST['address'], ENT_QUOTES, 'UTF-8');
    $phone = html_entity_decode($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $email = html_entity_decode($_POST['email'], ENT_QUOTES, 'UTF-8');
    $msg = html_entity_decode($_POST['msg'], ENT_QUOTES, 'UTF-8');
    $subject = html_entity_decode($_POST['subject'], ENT_QUOTES, 'UTF-8');
    $privacy_agreement = isset($_POST['privacy_agreement']) ? true : false;

    if(!$privacy_agreement) $field_notice['privacy_agreement'] = $pms_texts['REQUIRED_FIELD'];
    if($name == '') $field_notice['name'] = $pms_texts['REQUIRED_FIELD'];
    if($msg == '') $field_notice['msg'] = $pms_texts['REQUIRED_FIELD'];
    if($subject == '') $field_notice['subject'] = $pms_texts['REQUIRED_FIELD'];
    
    if($email == '' || !preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/i', $email)) $field_notice['email'] = $pms_texts['INVALID_EMAIL'];
    
    if(count($field_notice) == 0){

        $data = array();
        $data['id'] = '';
        $data['name'] = $name;
        $data['address'] = $address;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['subject'] = $subject;
        $data['msg'] = $msg;
        $data['add_date'] = time();
        $data['edit_date'] = null;

        $result_message = pms_db_prepareInsert($pms_db, 'pm_message', $data);
        $result_message->execute();
            
        $mail = pms_getMail($pms_db, 'CONTACT', array(
            '{name}' => $name,
            '{address}' => $address,
            '{phone}' => $phone,
            '{email}' => $email,
            '{msg}' => nl2br($msg)
        ));
        
        if($mail !== false && pms_sendMail(PMS_EMAIL, PMS_OWNER, $subject, $mail['content'], $email, $name))
            $msg_success .= $pms_texts['MAIL_DELIVERY_SUCCESS'];
        else
            $msg_error .= $pms_texts['MAIL_DELIVERY_FAILURE'];
    }else
        $msg_error .= $pms_texts['FORM_ERRORS'];
    
}else{
    $name = '';
    $address = '';
    $phone = '';
    $email = '';
    $subject = '';
    $msg = '';
    $privacy_agreement = false;
}
require(pms_getFromTemplate('common/header.php', false)); ?>

<script>
    var pms_locations = [
        <?php
        $result_location = $pms_db->query("SELECT * FROM pm_location WHERE checked = 1 AND pages REGEXP '(^|,)".$pms_page_id."(,|$)'");
        if($result_location !== false){
            $nb_locations = $pms_db->last_row_count();
            foreach($result_location as $i => $row){
                $location_name = $row['name'];
                $location_address = $row['address'];
                $location_lat = $row['lat'];
                $location_lng = $row['lng'];

                echo "['".addslashes($location_name)."', '".addslashes($location_address)."', '".$location_lat."', '".$location_lng."']";
                if($i+1 < $nb_locations) echo ",\n";
            }
        } ?>
    ];
</script>

<section id="page">
    
    <?php include(pms_getFromTemplate('common/page_header.php', false)); ?>
    
    <div id="content" class="clearfix">
        <div id="mapWrapper" data-marker="<?php echo pms_getFromTemplate('images/marker.png'); ?>"></div>
        <div class="container pt30 pb15">
            
            <?php
            if($page['text'] != ''){ ?>
                <div class="clearfix mb20"><?php echo $page['text']; ?></div>
                <?php
            } ?>

            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
            
            <div class="row">
                <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-user"></i></div>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlentities($name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $pms_texts['LASTNAME']." ".$pms_texts['FIRSTNAME']; ?> *">
                            </div>
                            <div class="field-notice" rel="name"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-envelope"></i></div>
                                <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $pms_texts['EMAIL']; ?> *">
                            </div>
                            <div class="field-notice" rel="email"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-home"></i></div>
                                <textarea class="form-control" name="address" placeholder="<?php echo $pms_texts['ADDRESS'].", ".$pms_texts['POSTCODE'].", ".$pms_texts['CITY']; ?>"><?php echo htmlentities($address, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="field-notice" rel="address"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-phone"></i></div>
                                <input type="text" class="form-control" name="phone" value="<?php echo htmlentities($phone, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $pms_texts['PHONE']; ?>">
                            </div>
                            <div class="field-notice" rel="phone"></div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-question"></i></div>
                                <input type="text" class="form-control" name="subject" value="<?php echo htmlentities($subject, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $pms_texts['SUBJECT']; ?> *">
                            </div>
                            <div class="field-notice" rel="subject"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-quote-left"></i></div>
                                <textarea class="form-control" name="msg" placeholder="<?php echo $pms_texts['MESSAGE']; ?> *" rows="4"><?php echo htmlentities($msg, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="field-notice" rel="msg"></div>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="privacy_agreement" value="1"<?php if($privacy_agreement) echo ' checked="checked"'; ?>> <?php echo $pms_texts['PRIVACY_POLICY_AGREEMENT']; ?>
                            <div class="field-notice" rel="privacy_agreement"></div>
                        </div>
                        <?php
                        if(PMS_CAPTCHA_PKEY != '' && PMS_CAPTCHA_SKEY != ''){ ?>
                            <div class="form-group">
                                <div class="input-group mb5"></div>
                                <div class="g-recaptcha" data-sitekey="<?php echo PMS_CAPTCHA_PKEY; ?>"></div>
                            </div>
                            <?php
                        } ?>   
                        <div class="form-group row">
                            <span class="col-sm-12"><button type="submit" class="btn btn-primary" name="send"><i class="fas fa-fw fa-paper-plane"></i> <?php echo $pms_texts['SEND']; ?></button> <i> * <?php echo $pms_texts['REQUIRED_FIELD']; ?></i></span>
                        </div>
                    </div>
                </form>
                <div class="col-sm-3">
                    <div class="hotBox" itemscope itemtype="http://schema.org/Corporation">
                        <h2 itemprop="name"><?php echo PMS_OWNER; ?></h2>
                        <address>
                            <p>
                                <?php if(PMS_ADDRESS != '') : ?><span class="fas fa-fw fa-map-marker"></span> <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo nl2br(PMS_ADDRESS); ?></span><br><?php endif; ?>
								<?php if(PMS_PHONE != "") : ?><span class="fas fa-fw fa-phone"></span> <a href="tel:<?php echo PMS_PHONE; ?>" itemprop="telephone" dir="ltr"><?php echo PMS_PHONE; ?></a><br><?php endif; ?>
                                <?php if(PMS_FAX != '') : ?><span class="fas fa-fw fa-fax"></span> <span itemprop="faxNumber" dir="ltr"><?php echo PMS_FAX; ?></span><br><?php endif; ?>
                                <?php if(PMS_EMAIL != '') : ?><span class="fas fa-fw fa-envelope"></span> <a itemprop="email" dir="ltr" href="mailto:<?php echo PMS_EMAIL; ?>"><?php echo PMS_EMAIL; ?></a><?php endif; ?>
                            </p>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
