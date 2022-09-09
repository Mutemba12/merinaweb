<?php
/**
 * Common file for Pandao CMS
 * gets the configuration values and defines the environment
 */
if(!pms_is_session_started()) session_start();

if(!defined('ADMIN')) define('ADMIN', false);

require_once('setenv.php');

$default_lang = 2;
$default_lang_tag = 'en';
$lang_alias = '';
$locale = 'en_GB';
$default_currency_code = 'USD';
$default_currency_sign = '$';
$default_currency_rate = 1;
$rtl_dir = false;
$pms_db = false;

if(is_file(SYSBASE.'common/config.php')){
    require_once(SYSBASE.'common/config.php');
    
    $admin_lang_file = SYSBASE.PMS_ADMIN_FOLDER.'/includes/langs/'.PMS_ADMIN_LANG_FILE;
    
    if(ADMIN && is_file($admin_lang_file)){
        $pms_texts = @parse_ini_file($admin_lang_file);
        if(is_null($pms_texts))
            $pms_texts = @parse_ini_string(file_get_contents($admin_lang_file));
    }
    
    try{
        $pms_db = new db('mysql:host='.PMS_DB_HOST.';port='.PMS_DB_PORT.';dbname='.PMS_DB_NAME.';charset=utf8', PMS_DB_USER, PMS_DB_PASS);
        $pms_db->exec('SET NAMES \'utf8\'');
    }catch(PDOException $e){
        if(ADMIN) $_SESSION['msg_error'][] = $pms_texts['DATABASE_ERROR'];
        else die('Unable to connect to the database. Please contact the webmaster or retry later.');
    }
}

if(!defined('PMS_ADMIN_FOLDER')) define('PMS_ADMIN_FOLDER', 'admin');

if(($pms_db !== false && pms_db_table_exists($pms_db, 'pm_%') === false) || !is_file(SYSBASE.'common/config.php')){
    header('Location: '.DOCBASE.PMS_ADMIN_FOLDER.'/setup.php');
    exit();
}else{
    if($pms_db === false) die('Unable to connect to the database. Please contact the webmaster or retry later.');
}

if(!ADMIN){
    $request_uri = (DOCBASE != '/') ? substr($_SERVER['REQUEST_URI'], strlen(DOCBASE)) : $_SERVER['REQUEST_URI'];
    $request_uri = trim($request_uri, '/');
    $pos = strpos($request_uri, '?');
    if($pos !== false) $request_uri = substr($request_uri, 0, $pos);
    
    define('REQUEST_URI', $request_uri);
}
    
if(isset($_SESSION['user']['id'])){
    $result_user = $pms_db->query('SELECT * FROM pm_user WHERE id = '.$pms_db->quote($_SESSION['user']['id']).' AND checked = 1');
    if($result_user !== false && $pms_db->last_row_count() > 0){
        $row = $result_user->fetch();
        $_SESSION['user']['id'] = $row['id'];
        $_SESSION['user']['login'] = $row['login'];
        $_SESSION['user']['email'] = $row['email'];
        $_SESSION['user']['type'] = $row['type'];
    }else
        unset($_SESSION['user']);
}

$result_currency = $pms_db->query('SELECT * FROM pm_currency');
if($result_currency !== false){
    foreach($result_currency as $i => $row){
        $currency_code = $row['code'];
        $currency_sign = $row['sign'];
        if($row['main'] == 1){
            $default_currency_code = $currency_code;
            $default_currency_sign = $currency_sign;
        }
        $pms_currencies[$currency_code] = $row;
    }
}
    
$result_lang = $pms_db->query('SELECT l.id AS lang_id, lf.id AS file_id, title, tag, file, locale, rtl, main FROM pm_lang as l, pm_lang_file as lf WHERE id_item = l.id AND l.checked = 1 AND file != \'\' ORDER BY l.rank');
if($result_lang !== false){
    foreach($result_lang as $i => $row){
        $lang_tag = $row['tag'];
        if($row['main'] == 1){
            $default_lang = $row['lang_id'];
            $default_lang_tag = $lang_tag;
            $rtl_dir = $row['rtl'];
            $locale = $row['locale'];
        }
        $row['file'] = DOCBASE.'medias/lang/big/'.$row['file_id'].'/'.$row['file'];
        $pms_langs[$lang_tag] = $row;
    }
}
$id_lang = $default_lang;
$lang_tag = $default_lang_tag;

if(!ADMIN && (PMS_MAINTENANCE_MODE == 0  || (isset($_SESSION['user']) && ($_SESSION['user']['type'] == 'administrator' || $_SESSION['user']['type'] == 'manager')))){
    if(PMS_LANG_ENABLED == 1){
        
        $uri = explode('/', REQUEST_URI);
        $lang_tag = $uri[0];
        
        if(!isset($pms_langs[$lang_tag])){
            
            if(preg_match('/$(index.php)?^/', str_replace(DOCBASE, '', $_SERVER['REQUEST_URI']))){
                
                if($lang_tag == ''){
                    if(isset($_COOKIE['PMS_LANG_TAG']) && isset($pms_langs[$_COOKIE['PMS_LANG_TAG']])){
                        header('HTTP/1.0 404 Not Found');
                        header('Location: '.DOCBASE.$_COOKIE['PMS_LANG_TAG']);
                        exit();
                    }else{
                        header('HTTP/1.0 404 Not Found');
                        header('Location: '.DOCBASE.$default_lang_tag);
                        exit();
                    }
                }else pms_err404(DOCBASE.'404.html');
                
            }elseif(isset($_SESSION['PMS_LANG_TAG']))
                $lang_tag = $_SESSION['PMS_LANG_TAG'];
            else
                $lang_tag = $default_lang_tag;
        }else{
            setcookie('PMS_LANG_TAG', $lang_tag, time()+25200);
            
            $_SESSION['PMS_LANG_TAG'] = $lang_tag;
        }
        if(isset($pms_langs[$lang_tag])){
            $id_lang = $pms_langs[$lang_tag]['lang_id'];
            $locale = $pms_langs[$lang_tag]['locale'];
            $rtl_dir = $pms_langs[$lang_tag]['rtl'];
        }
        $sublocale = substr($locale, 0, 2);
        if($sublocale == 'tr' || $sublocale == 'az') $locale = 'en_GB';
        $lang_alias = $lang_tag.'/';
    }
    
    $pms_texts = array();
    $result_text = $pms_db->query('SELECT * FROM pm_text WHERE lang = '.$id_lang.' GROUP BY id');
    foreach($result_text as $row)
        $pms_texts[$row['name']] = $row['value'];
            
    $widgets = array();
    $result_widget = $pms_db->query('SELECT * FROM pm_widget WHERE checked = 1 AND lang = '.$id_lang.' GROUP BY id ORDER BY `rank`');
    foreach($result_widget as $row)
        $widgets[$row['pos']][] = $row;
        
    $pms_pages = array();
    $pms_sys_pages = array();
    $pms_parents = array();
    $result_page = $pms_db->query('SELECT *
							FROM pm_page
							WHERE (checked = 1 OR checked = 0)
								AND lang = '.$id_lang.'
								AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.$id_lang.'(,|$)\')
								AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.$id_lang.'(,|$)\')
							ORDER BY `rank`');
    if($result_page !== false){
        foreach($result_page as $i => $row){

            $alias = $row['alias'];
            
            if($row['home'] != 1){
                $alias = pms_text_format($alias);
                $currequest = $alias;
            }else{
                $alias = "";
                $currequest = "";
            }
            
            $alias = trim($lang_alias.$alias, "/\\");
            $currequest = trim($lang_alias.$currequest, "/\\");
            
            $row['alias'] = $alias;
            $row['currequest'] = $currequest;
            if($row['system'] == 1) $pms_sys_pages[$row['page_model']] = $row;
            
            if($row['home'] == 1) $pms_homepage = $row;
            
            $row['articles'] = array();
            
            $pms_pages[$row['id']] = $row;
            $pms_parents[$row['id_parent']][] = $row['id'];
        }
    }
    
    define('URL_404', DOCBASE.$pms_sys_pages['404']['alias']);
    
    $articles = array();
    $result_article = $pms_db->query('SELECT *
								FROM pm_article
								WHERE id_page IN('.implode(',', array_keys($pms_pages)).')
									AND (checked = 1 OR checked = 3)
									AND (publish_date IS NULL || publish_date <= '.time().')
									AND (unpublish_date IS NULL || unpublish_date > '.time().')
									AND lang = '.$id_lang.'
									AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.$id_lang.'(,|$)\')
									AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.$id_lang.'(,|$)\')
								ORDER BY CASE WHEN publish_date IS NOT NULL THEN publish_date ELSE add_date END DESC');
    if($result_article !== false){
        foreach($result_article as $i => $row){
            
            $alias = $row['alias'];
            
            $full_alias = $pms_pages[$row['id_page']]['alias'].'/'.pms_text_format($alias);
            $row['alias'] = $full_alias;
            $articles[$row['id']] = $row;
            
            $pms_pages[$row['id_page']]['articles'][$row['id']] = $row;
        }
    }
    
    $pms_menus['main'] = array();
    $pms_menus['footer'] = array();
    $result_menu = $pms_db->query('SELECT * FROM pm_menu WHERE checked = 1 AND lang = '.$id_lang.' ORDER BY `rank`');
    if($result_menu !== false){
        foreach($result_menu as $row){
            
            if(($row['item_type'] == 'page' && isset($pms_pages[$row['id_item']]) && $pms_pages[$row['id_item']]['checked'] == 1)
            || ($row['item_type'] == 'article' && isset($articles[$row['id_item']]))
            || $row['item_type'] == 'url'
            || $row['item_type'] == 'none'){
                
                $href = pms_get_nav_url($row);
                $row['href'] = $href;
                
                $target = (strpos($href, 'http') !== false) ? '_blank' : '_self';
                if(strpos($href, pms_getUrl(true)) !== false) $target = '_self';
                $row['target'] = $target;
            
                if($row['main'] == 1) $pms_menus['main'][$row['id']] = $row;
                if($row['footer'] == 1) $pms_menus['footer'][$row['id']] = $row;
            }
        }
    }
}

$currency_code = (isset($_SESSION['currency']['code'])) ? $_SESSION['currency']['code'] : $default_currency_code;
$currency_sign = (isset($_SESSION['currency']['sign'])) ? $_SESSION['currency']['sign'] : $default_currency_sign;
$currency_rate = (isset($_SESSION['currency']['rate'])) ? $_SESSION['currency']['rate'] : $default_currency_rate;

date_default_timezone_set(PMS_TIME_ZONE);

if(setlocale(LC_ALL, $locale.'.UTF-8', $locale) === false){
    $locale = 'en_GB';
    setlocale(LC_ALL, $locale.'.UTF-8', $locale);
}

define('PMS_DEFAULT_CURRENCY_CODE', $default_currency_code);
define('PMS_DEFAULT_CURRENCY_SIGN', $default_currency_sign);
define('PMS_CURRENCY_CODE', $currency_code);
define('PMS_CURRENCY_SIGN', $currency_sign);
define('PMS_CURRENCY_RATE', $currency_rate);
define('PMS_DEFAULT_LANG', $default_lang);
define('PMS_LOCALE', $locale);
define('PMS_LANG_ID', $id_lang);
define('PMS_LANG_TAG', $lang_tag);
define('PMS_LANG_ALIAS', $lang_alias);
define('PMS_RTL_DIR', $rtl_dir);

$pms_allowable_file_exts = array(
    'pdf' => 'pdf.png',
    'doc' => 'doc.png',
    'docx' => 'doc.png',
    'odt' => 'doc.png',
    'xls' => 'xls.png',
    'xlsx' => 'xls.png',
    'ods' => 'xls.png',
    'ppt' => 'ppt.png',
    'pptx' => 'ppt.png',
    'odp' => 'ppt.png',
    'txt' => 'txt.png',
    'csv' => 'txt.png',
    'jpg' => 'img.png',
    'jpeg' => 'img.png',
    'png' => 'img.png',
    'gif' => 'img.png',
    'swf' => 'swf.png'
);
