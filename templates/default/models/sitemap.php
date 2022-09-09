<?php require(pms_getFromTemplate("common/header.php", false)); ?>

<section id="page">
    
    <?php include(pms_getFromTemplate("common/page_header.php", false)); ?>
    
    <div id="content" class="pt30 pb30">
        <div class="container">
            <?php echo $page['text']; ?>
            
            <ul class="nostyle">
                <?php
                function subPages($id_parent, $menu)
                { ?>
                    <ul>
                        <?php
                        foreach($menu as $nav_id => $nav){
                            if($nav['id_parent'] == $id_parent){ ?>
                                <li>
                                    <?php
                                    $hasChildNav = pms_has_child_nav($nav_id, $menu); ?>
                                    <a href="<?php echo $nav['href']; ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
                                    <?php if($hasChildNav) subPages($nav_id, $menu); ?>
                                </li>
                                <?php
                            }
                        } ?>
                    </ul>
                    <?php
                }
                
                $uniqid = array();
                foreach($pms_menus as $pos => $menu){
                    foreach($menu as $nav_id => $nav){
                        if(!in_array($nav['id_item'], $uniqid) && $nav['item_type'] == "page" && (empty($nav['id_parent']) || $nav['id_parent'] == $pms_homepage['id'])){
                            $uniqid[] = $nav['id_item']; ?>
                            <li>
                                <?php
                                if($pms_pages[$nav['id_item']]['home'] == 1){ ?>
                                    <a href="<?php echo DOCBASE.trim(PMS_LANG_ALIAS, "/"); ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
                                    <?php
                                }else{
                                    $hasChildNav = pms_has_child_nav($nav_id, $pms_menus['main']); ?>
                                    <a href="<?php echo $nav['href']; ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
                                    <?php if($hasChildNav) subPages($nav_id, $pms_menus['main']);
                                } ?>
                            </li>
                            <?php
                        }
                    }
                } ?>
            </ul>
        </div>
    </div>
</section>
