<?php debug_backtrace() || die ('Direct access not permitted'); ?>
<!DOCTYPE html>
<html lang="<?php echo PMS_LANG_TAG; ?>">
<head>
    <meta charset="UTF-8">

    <title><?php echo $title_tag; ?></title>

    <?php
    if(isset($article)) $meta_descr = pms_strtrunc(pms_rip_tags($article['text']), 155);
    elseif($page['descr'] != "") $meta_descr = $page['descr'];
    else $meta_descr = pms_strtrunc(pms_rip_tags($page['text']), 155); ?>

    <meta name="description" content="<?php echo $meta_descr; ?>">
    
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo $title_tag; ?>">
    <meta itemprop="description" content="<?php echo $meta_descr; ?>">
    <?php
    if(isset($page_img)){ ?>
        <meta itemprop="image" content="<?php echo $page_img; ?>">
        <?php
    } ?>
    
    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo $title_tag; ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo pms_getUrl(); ?>">
    <?php
    if(isset($page_img)){ ?>
        <meta property="og:image" content="<?php echo $page_img; ?>">
        <?php
    } ?>
    <meta property="og:description" content="<?php echo $meta_descr; ?>">
    <meta property="og:site_name" content="<?php echo PMS_SITE_TITLE; ?>">
    <?php
    if(isset($publish_date) && isset($edit_date)){ ?>
        <meta property="article:published_time" content="<?php echo date('c', $publish_date); ?>">
        <meta property="article:modified_time" content="<?php echo date('c', $edit_date); ?>">
        <?php
    } ?>
    <?php
    if($pms_article_id > 0){ ?>
        <meta property="article:section" content="<?php echo $page['title']; ?>">
        <?php
    } ?>
    <?php
    if(isset($article_tags) && $article_tags != ''){ ?>
        <meta property="article:tag" content="<?php echo $article_tags; ?>">
        <?php
    } ?>
    <meta property="article:author" content="<?php echo PMS_OWNER; ?>">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="<?php echo $title_tag; ?>">
    <meta name="twitter:description" content="<?php echo $meta_descr; ?>">
    <meta name="twitter:creator" content="@author_handle">
    <?php
    if(isset($page_img)){ ?>
        <meta name="twitter:image:src" content="<?php echo $page_img; ?>">
        <?php
    } ?>
    
    <meta name="robots" content="<?php if($page['robots'] != "") echo $page['robots']; else echo 'index, follow'; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?php
    if(PMS_AUTOGEOLOCATE){ ?>
        <meta name="autogeolocate" content="true">
        <?php
    }
    if(PMS_GMAPS_API_KEY != ''){ ?>
        <meta name="gmaps_api_key" content="<?php echo PMS_GMAPS_API_KEY; ?>">
        <?php
    } ?>
    
    <link rel="icon" type="image/png" href="<?php echo pms_getFromTemplate('images/favicon.png'); ?>">
    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <?php
    if(PMS_RTL_DIR){ ?>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.2.0-rc2/css/bootstrap-rtl.min.css">
        <?php
    } ?>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    
    <?php
    //CSS required by the current model
    if(isset($pms_stylesheets)){
        foreach($pms_stylesheets as $stylesheet){ ?>
            <link rel="stylesheet" href="<?php echo $stylesheet['file']; ?>" media="<?php echo $stylesheet['media']; ?>">
            <?php
        }
    } ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo DOCBASE; ?>common/css/shortcodes.css">
    <link rel="stylesheet" href="<?php echo pms_getFromTemplate("css/layout.css"); ?>">
    <link rel="stylesheet" href="<?php echo pms_getFromTemplate("css/colors.css"); ?>" id="colors">
    <link rel="stylesheet" href="<?php echo pms_getFromTemplate("css/main.css"); ?>">
    <link rel="stylesheet" href="<?php echo pms_getFromTemplate("css/custom.css"); ?>">
    
    <script src="//code.jquery.com/jquery-1.12.4.min.js"></script>

    <?php
    if(PMS_ANALYTICS_CODE != '' && mb_strstr(PMS_ANALYTICS_CODE, '<script') === false)
        echo '<script>'.stripslashes(PMS_ANALYTICS_CODE).'</script>';
    else
        echo stripslashes(PMS_ANALYTICS_CODE); ?>
</head>
<body id="page-<?php echo $pms_page_id; ?>" itemscope itemtype="http://schema.org/WebPage"<?php if(PMS_RTL_DIR) echo ' dir="rtl"'; ?>>

<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="<?php echo htmlentities($title_tag, ENT_QUOTES); ?>">
<meta itemprop="description" content="<?php echo htmlentities($meta_descr, ENT_QUOTES); ?>">
<?php
if(isset($page_img)){ ?>
    <meta itemprop="image" content="<?php echo $page_img; ?>">
    <?php
} ?>

<div id="loader-wrapper"><div id="loader"></div></div>

<?php
if(PMS_ENABLE_COOKIES_NOTICE == 1 && !isset($_COOKIE['cookies_enabled'])){ ?>
    <div id="cookies-notice">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $pms_texts['COOKIES_NOTICE']; ?>
                    <button class="btn btn-success btn-xs">OK</button>
                </div>
            </div>
        </div>
    </div>
    <?php
} ?>
<header class="navbar-fixed-top">
    <div id="mainHeader">
        <div class="container-fluid">
			<div id="secondMenu">
                <ul class="nav navbar-nav">
                    <li class="primary btn-nav">
                        <?php
                        if(isset($_SESSION['user'])){ ?>
                            <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>" class="ajax-form">
                                <div class="dropdown">
                                    <a class="firstLevel dropdown-toggle" data-toggle="dropdown" href="#">
                                        <i class="fas fa-fw fa-user"></i>
                                        <span class="hidden-sm hidden-md">
                                            <?php
                                            if($_SESSION['user']['login'] != '') echo $_SESSION['user']['login'];
                                            else echo $_SESSION['user']['email']; ?>
                                        </span>
                                        <span class="fas fa-fw fa-caret-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu" id="user-menu">
                                        <?php
                                        if($_SESSION['user']['type'] == 'registered'){ ?>
                                            <li><a href="<?php echo DOCBASE.$pms_sys_pages['account']['alias']; ?>"><i class="fas fa-fw fa-user"></i> <?php echo $pms_sys_pages['account']['name']; ?></a></li>
                                            <?php
                                        } ?>
                                        <li><a href="#" class="sendAjaxForm" data-action="<?php echo DOCBASE; ?>templates/<?php echo PMS_TEMPLATE; ?>/common/register/logout.php" data-refresh="true"><i class="fas fa-fw fa-power-off"></i> <?php echo $pms_texts['LOG_OUT']; ?></a></li>
                                    </ul>
                                </div>
                            </form>
                            <?php
                        }else{ ?>
                            <a class="popup-modal firstLevel" href="#user-popup">
                                <i class="fas fa-fw fa-power-off"></i>
                            </a>
                            <?php
                        } ?>
                    </li>
                    <?php
                    if(PMS_LANG_ENABLED){
                        if(count($pms_langs) > 0){ ?>
                            <li class="primary btn-nav">
                                <div class="dropdown">
                                    <a class="firstLevel dropdown-toggle" data-toggle="dropdown" href="#">
                                        <img src="<?php echo $pms_langs[PMS_LANG_TAG]['file']; ?>" alt="<?php echo $pms_langs[PMS_LANG_TAG]['title']; ?>"><span class="hidden-sm hidden-md"> <?php echo $pms_langs[PMS_LANG_TAG]['title']; ?></span> <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <?php
                                        foreach($pms_langs as $row){
                                            $title_lang = $row['title']; ?>
                                            <li>
                                                <a href="<?php echo DOCBASE.$row['tag']; ?>">
                                                    <img src="<?php echo $row['file']; ?>" alt="<?php echo $title_lang; ?>"> <?php echo $title_lang; ?>
                                                </a>
                                            </li>
                                            <?php
                                        } ?>
                                    </ul>
                                </div>
                            </li>
                            <?php
                        }
                    }
                    if(PMS_CURRENCY_ENABLED){
                        if(count($pms_currencies) > 0){ ?>
                            <li class="primary btn-nav">
                                <div class="dropdown">
                                    <a class="firstLevel dropdown-toggle" data-toggle="dropdown" href="#">
                                        <span><?php echo PMS_CURRENCY_CODE; ?></span><span class="hidden-sm hidden-md"> <?php echo PMS_CURRENCY_SIGN; ?></span> <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <?php
                                        foreach($pms_currencies as $row){ ?>
                                            <li>
                                                <a href="<?php echo pms_getUrl(); ?>" data-action="<?php echo DOCBASE.'includes/change_currency.php'; ?>?curr=<?php echo $row['id']; ?>" class="ajax-link<?php if(!isset($_SESSION['currency']['code'])) echo ' currency-'.$row['code']; ?>">
                                                    <?php echo $row['code'].' '.$row['sign']; ?>
                                                </a>
                                            </li>
                                            <?php
                                        } ?>
                                    </ul>
                                </div>
                            </li>
                            <?php
                        }
                    } ?>
                    <li class="primary btn-nav">
                        <div class="dropdown">
                            <a class="firstLevel dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fas fa-fw fa-search"></i> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li>
                                    <?php $csrf_token = pms_get_token('search'); ?>

                                    <form method="post" action="<?php echo DOCBASE.$pms_sys_pages['search']['alias']; ?>" class="form-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <div class="input-group searchWrapper">
                                            <input type="text" class="form-control" name="global-search" placeholder="<?php echo $pms_texts['SEARCH']; ?>">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary" name="send"><i class="fas fa-fw fa-search"></i></button>
                                            </span>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <div id="user-popup" class="white-popup-block mfp-hide">
                    <div class="fluid-container">
                        <!--<div class="row">
                            <div class="col-xs-12 mb20 text-center">
                                <a class="btn fblogin" href="#"><i class="fas fa-fw fa-facebook"></i> <?php echo $pms_texts['LOG_IN_WITH_FACEBOOK']; ?></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 mb20 text-center">
                                - <?php echo $pms_texts['OR']; ?> -
                            </div>
                        </div>-->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="login-form">
                                    <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>" class="ajax-form">
                                        <div class="alert alert-success" style="display:none;"></div>
                                        <div class="alert alert-danger" style="display:none;"></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-user"></i></div>
                                                <input type="text" class="form-control" name="user" value="" placeholder="<?php echo $pms_texts['USERNAME'].' '.strtolower($pms_texts['OR']).' '.$pms_texts['EMAIL']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="user"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-lock"></i></div>
                                                <input type="password" class="form-control" name="pass" value="" placeholder="<?php echo $pms_texts['PASSWORD']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="pass"></div>
                                        </div>
                                        <div class="row mb10">
                                            <div class="col-sm-7 text-left">
                                                <a class="open-pass-form" href="#"><?php echo $pms_texts['FORGOTTEN_PASSWORD']; ?></a><br>
                                                <a class="open-signup-form" href="#"><?php echo $pms_texts['I_SIGN_UP']; ?></a>
                                            </div>
                                            <div class="col-sm-5 text-right">
                                                <a href="#" class="btn btn-default sendAjaxForm" data-action="<?php echo pms_getFromTemplate('common/register/login.php'); ?>" data-refresh="true"><i class="fas fa-fw fa-power-off"></i> <?php echo $pms_texts['LOG_IN']; ?></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="signup-form">
                                    <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>" class="ajax-form">
                                        <div class="alert alert-success" style="display:none;"></div>
                                        <div class="alert alert-danger" style="display:none;"></div>
                                        <input type="hidden" name="signup_type" value="quick" class="noreset">
                                        <input type="hidden" name="signup_redirect" value="<?php echo pms_getUrl(true).DOCBASE.$pms_sys_pages['account']['alias']; ?>" class="noreset">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-user"></i></div>
                                                <input type="text" class="form-control" name="username" value="" placeholder="<?php echo $pms_texts['USERNAME']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="username"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-envelope"></i></div>
                                                <input type="text" class="form-control" name="email" value="" placeholder="<?php echo $pms_texts['EMAIL']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="email"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-lock"></i></div>
                                                <input type="password" class="form-control" name="password" value="" placeholder="<?php echo $pms_texts['PASSWORD']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="password"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-lock"></i></div>
                                                <input type="password" class="form-control" name="password_confirm" value="" placeholder="<?php echo $pms_texts['PASSWORD_CONFIRM']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="password_confirm"></div>
                                        </div>
                                        <div class="row mb10">
                                            <div class="col-sm-7 text-left">
                                                <a class="open-login-form" href="#"><?php echo $pms_texts['ALREADY_HAVE_ACCOUNT']; ?></a>
                                            </div>
                                            <div class="col-sm-5 text-right">
                                                <a href="#" class="btn btn-default sendAjaxForm" data-action="<?php echo pms_getFromTemplate('common/register/signup.php'); ?>" data-clear="true"><i class="fas fa-fw fa-power-off"></i> <?php echo $pms_texts['SIGN_UP']; ?></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="pass-form">
                                    <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>" class="ajax-form">
                                        <div class="alert alert-success" style="display:none;"></div>
                                        <div class="alert alert-danger" style="display:none;"></div>
                                        <p><?php echo $pms_texts['NEW_PASSWORD_NOTICE']; ?></p>
                                            
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fas fa-fw fa-envelope"></i></div>
                                                <input type="text" class="form-control" name="email" value="" placeholder="<?php echo $pms_texts['EMAIL']; ?> *">
                                            </div>
                                            <div class="field-notice" rel="email"></div>
                                        </div>
                                        <div class="row mb10">
                                            <div class="col-sm-7 text-left">
                                                <a class="open-login-form" href="#"><?php echo $pms_texts['LOG_IN']; ?></a><br>
                                                <a class="open-signup-form" href="#"><?php echo $pms_texts['I_SIGN_UP']; ?></a>
                                            </div>
                                            <div class="col-sm-5 text-right">
                                                <a href="#" class="btn btn-default sendAjaxForm" data-action="<?php echo pms_getFromTemplate('common/register/reset.php'); ?>" data-refresh="false"><i class="fas fa-fw fa-power-off"></i> <?php echo $pms_texts['NEW_PASSWORD']; ?></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="mainMenu" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php
                    function subMenu($id_parent, $menu)
                    { ?>
                        <span class="dropdown-btn visible-xs"></span>
                        <ul class="subMenu">
                            <?php
                            foreach($menu as $nav_id => $nav){
                                if($nav['id_parent'] == $id_parent){ ?>
                                    <li>
                                        <?php
                                        $hasChildNav = pms_has_child_nav($nav_id, $menu); ?>
                                        <a class="<?php if($hasChildNav) echo 'hasSubMenu'; ?>" href="<?php echo $nav['href']; ?>" target="<?php echo $nav['target']; ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
                                        <?php if($hasChildNav) subMenu($nav_id, $menu); ?>
                                    </li>
                                    <?php
                                }
                            } ?>
                        </ul>
                        <?php
                    }
                    
                    $top_nav_id = pms_get_top_nav_id($pms_menus['main']);
                    foreach($pms_menus['main'] as $nav_id => $nav){
                        if(empty($nav['id_parent']) || @$pms_menus['main'][$nav['id_parent']]['id_item'] == $pms_homepage['id']){ ?>
                            <li class="primary nav-<?php echo $nav_id; ?>">
                                <?php
                                if($nav['item_type'] == 'page' && $pms_pages[$nav['id_item']]['home'] == 1){ ?>
                                    <a class="firstLevel<?php if($ishome) echo ' active'; ?>" href="<?php echo DOCBASE.trim(PMS_LANG_ALIAS, '/'); ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
                                    <?php
                                }else{
                                    $hasChildNav = pms_has_child_nav($nav_id, $pms_menus['main']); ?>
                                    <a class="dropdown-toggle disabled firstLevel<?php if($hasChildNav) echo ' hasSubMenu'; if($top_nav_id == $nav_id) echo ' active'; ?>" href="<?php echo $nav['href']; ?>" target="<?php echo $nav['target']; ?>" title="<?php echo $nav['title']; ?>">
                                        <?php
                                        echo $nav['name'];
                                        if($hasChildNav){ ?>
                                            <i class="fa fa-fw fa-angle-down hidden-xs"></i>
                                            <?php
                                        } ?>
                                    </a>
                                    <?php if($hasChildNav) subMenu($nav_id, $pms_menus['main']);
                                } ?>
                            </li>
                            <?php
                        }
                    } ?>
                </ul>
            </div>
            <div class="navbar navbar-default">
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo DOCBASE.trim(PMS_LANG_ALIAS, '/'); ?>" title="<?php echo $pms_homepage['title']; ?>"><img src="<?php echo pms_getFromTemplate('images/logo.png'); ?>" alt="<?php echo PMS_SITE_TITLE; ?>"></a>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
