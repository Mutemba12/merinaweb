<?php
define('ADMIN', true);
require_once('../common/lib.php');
require_once('../common/define.php');
define('TITLE_ELEMENT', $pms_texts['DASHBOARD'].' - '.$pms_texts['LOGIN']);

$action = (isset($_GET['action'])) ? $_GET['action'] : '';

if($action == 'logout' && isset($_SESSION['user'])) unset($_SESSION['user']);

if(isset($_SESSION['user'])){
    if($_SESSION['user']['type'] != 'registered'){
        header('Location: index.php');
        exit();
    }else
        unset($_SESSION['user']);
}

if($pms_db !== false && isset($_POST['login'])){
    $user = htmlentities($_POST['user'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    
    if(pms_check_token('/'.PMS_ADMIN_FOLDER.'/login.php', 'login', 'post')){
        
        $result_user = $pms_db->query('SELECT * FROM pm_user WHERE login = '.$pms_db->quote($user).' AND pass = '.$pms_db->quote(md5($password)).' AND checked = 1');
        if($result_user !== false && $pms_db->last_row_count() > 0){
            $row = $result_user->fetch();
            $_SESSION['user']['id'] = $row['id'];
            $_SESSION['user']['login'] = $user;
            $_SESSION['user']['email'] = $row['email'];
            $_SESSION['user']['type'] = $row['type'];
            $_SESSION['user']['add_date'] = $row['add_date'];
            header('Location: index.php');
            exit();
        }else
            $_SESSION['msg_error'][] = $pms_texts['LOGIN_FAILED'];
    }else
        $_SESSION['msg_error'][] = $pms_texts['BAD_TOKEN2'];
}

if($pms_db !== false && isset($_POST['reset'])){
    
    if(defined('PMS_DEMO') && PMS_DEMO == 1)
        $_SESSION['msg_error'][] = 'This action is disabled in the demo mode';
    else{
        $email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');

        if(pms_check_token('/'.PMS_ADMIN_FOLDER.'/login.php', 'login', 'post')){

            $result_user = $pms_db->query('SELECT * FROM pm_user WHERE email = '.$pms_db->quote($email).' AND checked = 1');
            if($result_user !== false && $pms_db->last_row_count() > 0){
                $row = $result_user->fetch();
                $url = pms_getUrl();
                $new_pass = pms_genPass(6);
                $mailContent = '
                <p>Hi,<br>You requested a new password from <a href=\"'.$url.'\" target=\"_blank\">'.$url.'</a><br>
                Bellow, your new connection informations<br>
                Username: '.$row['login'].'<br>
                Password: <b>'.$new_pass.'</b><br>
                You can modify this random password in the settings via the manager.</p>';
                if(pms_sendMail($email, $row['name'], 'Your new password', $mailContent) !== false)
                    $pms_db->query('UPDATE pm_user SET pass = '.$pms_db->quote(md5($new_pass)).' WHERE id = '.$row['id']);
            }
            $_SESSION['msg_success'][] = 'A new password has been sent to your e-mail.';
        }else
            $_SESSION['msg_error'][] = 'Bad token! Thank you for re-trying by clicking on "New password".';
    }
}

$csrf_token = pms_get_token('login'); ?>
<!DOCTYPE html>
<head>
    <?php include('includes/inc_header_common.php'); ?>
</head>
<body class="white">
    <div class="container">
        <form id="form" class="form-horizontal" role="form" action="login.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="col-sm-3 col-md-4"></div>
            <div class="col-sm-6 col-md-4" id="loginWrapper">
                <div id="logo">
                    <img src="images/logo-admin.png">
                </div>
                <div id="login">
                    <div class="alert-container">
                        <div class="alert alert-success alert-dismissable"></div>
                        <div class="alert alert-warning alert-dismissable"></div>
                        <div class="alert alert-danger alert-dismissable"></div>
                    </div>
                    <?php
                    if($action == 'reset'){ ?>
                        <p>Please enter your e-mail address corresponding to your account. A new password will be sent to you by e-mail.</p>
                        <div class="row">
                            <label class="col-sm-12">
                                E-mail
                            </label>
                        </div>
                        <div class="row mb10">
                            <div class="col-sm-12">
                                <input class="form-control" type="text" value="" name="email">
                            </div>
                        </div>
                        <div class="row mb10">
                            <div class="col-xs-3 text-left">
                                <a href="login.php"><i class="fas fa-fw fa-power-off"></i> Login</a>
                            </div>
                            <div class="col-xs-9 text-right">
                                <button class="btn btn-default" type="submit" value="" name="reset"><i class="fas fa-fw fa-sync"></i> New password</button>
                            </div>
                        </div>
                        <?php
                    }else{
						if(defined('PMS_DEMO') && PMS_DEMO == 1) echo '<div class="alert alert-info text-center">PMS_DEMO &nbsp;&nbsp; <i class="fa fa-fw fa-user"></i> <i>admin</i>&nbsp; | &nbsp;<i class="fa fa-fw fa-lock"></i> <i>admin123</i></div>'; ?>
                        <div class="row">
                            <label class="col-sm-12">
                                <?php echo $pms_texts['USERNAME']; ?>
                            </label>
                        </div>
                        <div class="row mb10">
                            <div class="col-sm-12">
                                <input class="form-control" type="text" value="" name="user">
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-12">
                                <?php echo $pms_texts['PASSWORD']; ?>
                            </label>
                        </div>
                        <div class="row mb10">
                            <div class="col-sm-12">
                                <input class="form-control" type="password" value="" name="password">
                            </div>
                        </div>
                        <div class="row mb10">
                            <div class="col-sm-7 text-left">
                                <a href="login.php?action=reset">Remember password&nbsp;?</a>
                            </div>
                            <div class="col-sm-5 text-right">
                                <button class="btn btn-default" type="submit" value="" name="login"><i class="fas fa-fw fa-power-off"></i> <?php echo $pms_texts['LOGIN']; ?></button>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
            <div class="col-sm-3 col-md-4"></div>
        </form>
    </div>
</body>
</html>
<?php
$_SESSION['msg_error'] = array();
$_SESSION['msg_success'] = array();
$_SESSION['msg_notice'] = array(); ?>
