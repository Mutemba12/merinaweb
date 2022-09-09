<?php
/**
 * Display the right model of the template according to the url
 */
require('common/lib.php');
require('common/define.php');

if(PMS_MAINTENANCE_MODE == 0 || (isset($_SESSION['user']) && ($_SESSION['user']['type'] == 'administrator' || $_SESSION['user']['type'] == 'manager'))){

    $uri = preg_split('#[\\\\/]#', REQUEST_URI);
    $err404 = false;
    $ishome = false;
    $page = null;
    $article = null;
    $pms_page_id = 0;
    $pms_article_id = 0;
    $page_alias = '';
    $article_alias = '';

    $count_uri = count($uri);

    if((PMS_LANG_ENABLED && $count_uri == 1) || (!PMS_LANG_ENABLED && $uri[0] == '')) $ishome = true;
    else{
        $i = (PMS_LANG_ENABLED) ? 1 : 0;
        $page_alias = trim(PMS_LANG_ALIAS.$uri[$i], '/\\');
        if($count_uri > $i+2) pms_err404();
        if(isset($uri[$i+1])) $article_alias = $uri[$i+1];
    }
    
    foreach($articles as $id => $row){
        //current article
        if($article_alias != '' && $article_alias == substr($row['alias'], strrpos($row['alias'], '/')+1)){
            $pms_article_id = $row['id'];
            $article = $row;
        }
    }

    $found = false;
    if(!empty($pms_pages)){
        foreach($pms_pages as $row){
            //current page
            if(($ishome && $row['home'] == 1) XOR ($row['alias'] != '' && $page_alias == $row['alias'])){
                $pms_page_id = $row['id'];
                if($article_alias == '' && $row['currequest'] != REQUEST_URI) pms_err404();
                else{
                    $page = $row;
                    $found = true;
                }
            }
        }
    }
    
    if($found === false) pms_err404();

    $title_tag = $page['title_tag'];

    if($article_alias != '' && $page['article_model'] == '') pms_err404();
    if($article_alias == '' && $page['page_model'] == '') pms_err404();

    if($article_alias != '') $page_model = $page['article_model'];
    else $page_model = $page['page_model'];
                        
    $breadcrumbs = array();
    $id_parent = $page['id_parent'];
    while(isset($pms_parents[$id_parent])){
        if($id_parent > 0 && $id_parent != $pms_homepage['id']){
            $breadcrumbs[] = $id_parent;
            $id_parent = $pms_pages[$id_parent]['id_parent'];
        }else break;
    }

    $breadcrumbs = array_reverse($breadcrumbs);

    $page_model = SYSBASE.'templates/'.PMS_TEMPLATE.'/models/'.str_replace('_','/',$page_model).'.php';
    
    if(is_file($page_model)) include($page_model);

    require(SYSBASE.'templates/'.PMS_TEMPLATE.'/common/footer.php');
}else{
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    if(DOCBASE.REQUEST_URI != DOCBASE) header('Location: '.DOCBASE);
    require(SYSBASE.'templates/'.PMS_TEMPLATE.'/maintenance.php');
}

if(ob_get_level() > 0) ob_flush();
