<?php debug_backtrace() || die ("Direct access not permitted"); ?>
<header class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                if($pms_article_id == 0){
                    $page_title = $page['title'];
                    $page_subtitle = $page['subtitle'];
                    $page_name = $page['name']; ?>
                    
                    <h1 itemprop="name"><?php echo $page['title']; ?></h1>
                    <?php
                }else{
                    $page_name = $page_title; ?>
                    
                    <h1 itemprop="name"><?php echo $page_title; ?></h1>
                    <?php
                    if($page_subtitle == '') echo '<p class="lead mb0">'.$page['title'].'</p>';
                }
                if($page_subtitle != "") echo "<p class=\"lead mb0\">".$page_subtitle."</p>"; ?>
            </div>
            <div class="col-md-<?php echo (PMS_RTL_DIR) ? 12 : 4; ?> hidden-xs">
                <div itemprop="breadcrumb" class="breadcrumb clearfix">
                    
                    <a href="<?php echo DOCBASE.trim(PMS_LANG_ALIAS, "/"); ?>" title="<?php echo $pms_homepage['title']; ?>"><?php echo $pms_homepage['name']; ?></a>
                    
                    <?php
                    foreach($breadcrumbs as $id_parent){
                        if(isset($pms_pages[$id_parent])){
                            $parent = $pms_pages[$id_parent]; ?>
                            <a href="<?php echo DOCBASE.$parent['alias']; ?>" title="<?php echo $parent['title']; ?>"><?php echo $parent['name']; ?></a>
                            <?php
                        }
                    }
                    if($pms_article_id > 0){ ?>
                        <a href="<?php echo DOCBASE.$page['alias']; ?>" title="<?php echo $page['title']; ?>"><?php echo $page['name']; ?></a>
                        <?php
                    } ?>
                    
                    <span><?php echo $page_name; ?></span>
                </div>
                <?php
                /*
                if($pms_article_id > 0){ ?>
                    <a href="<?php echo DOCBASE.$page['alias']; ?>" class="btn btn-sm btn-primary pull-right" title="<?php echo $page['title']; ?>"><i class="fa fa-angle-double-left"></i><?php echo $pms_texts['BACK']; ?></a>
                    <?php
                }*/ ?>
            </div>
        </div>
    </div>
</header>
