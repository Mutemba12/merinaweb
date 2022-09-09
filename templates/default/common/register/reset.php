<?php
/**
 * Script called (Ajax) on reset password
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");

if(isset($_GET['token']) && isset($_GET['id']) && is_numeric($_GET['id'])){
    $result_token = $pms_db->query("SELECT * FROM pm_user WHERE token = ".$pms_db->quote(htmlentities($_GET['token'], ENT_COMPAT, "UTF-8"))." AND id = ".$_GET['id']." AND checked = 1");
    if($result_token !== false && $pms_db->last_row_count() > 0){
        $row = $result_token->fetch();
        $new_pass = pms_genPass(6);
        $mailContent = "
        <p>Hi,<br>You requested a new password<br>
        Bellow, your new connection informations<br>
        Username: ".$row['login']."<br>
        Password: <b>".$new_pass."</b><br>
        You can modify this random password in the settings via the manager.</p>";
        if(pms_sendMail($row['email'], $row['lastname']." ".$row['firstname'], "Your new password", $mailContent) !== false){
            $pms_db->query("UPDATE pm_user SET token = '', pass = '".md5($new_pass)."' WHERE id = ".$row['id']);
            header("Location: ".DOCBASE.PMS_LANG_ALIAS);
            exit();
        }
    }
}elseif(isset($_POST['email'])){
        
    $response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");

    $email = htmlentities($_POST['email'], ENT_COMPAT, "UTF-8");

    $result_user = $pms_db->query("SELECT * FROM pm_user WHERE email = ".$pms_db->quote($email)." AND checked = 1");
    if($result_user !== false && $pms_db->last_row_count() > 0){
        $row = $result_user->fetch();
        $token = md5(uniqid($email, true));
        $mailContent = "
        <p>Hi,<br>You requested a new password<br>
        Click on the link bellow to generate a new password:<br>
        <a href=".pms_getUrl()."?token=".$token."&id=".$row['id'].">Generate my new password</a></p>";
        if(pms_sendMail($email, $row['lastname']." ".$row['firstname'], "New password request", $mailContent) !== false){
            $pms_db->query("UPDATE pm_user SET token = '".$token."' WHERE id = ".$row['id']);
            $response['success'] = "A link has been sent to your e-mail.";
        }
    }else
        $response['error'] = $pms_texts['INVALID_EMAIL'];
        
    echo json_encode($response);
}
