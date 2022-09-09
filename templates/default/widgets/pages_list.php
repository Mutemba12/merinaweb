<?php
debug_backtrace() || die ("Direct access not permitted");

/*
if(isset($pms_parents[$pms_page_id])){ ?>
    <ul class="mt0 mb20" id="pages-list">
        <?php
        foreach($pms_parents[$pms_page_id] as $id){ ?>
            <li><a href="<?php echo DOCBASE.$pms_pages[$id]['alias']; ?>"><?php echo $pms_pages[$id]['name']; ?></a></li>
            <?php
        } ?>
    </ul>
    <?php
}*/
if($page['id_parent'] != 0 && $page['id_parent'] != $pms_homepage['id'] && isset($pms_parents[$page['id_parent']])){ ?>
    <ul class="mt20 mb20 page-list page-list-aside">
        <?php
        foreach($pms_parents[$page['id_parent']] as $id){
            if($id != $pms_page_id){ ?>
                <li><a href="<?php echo DOCBASE.PMS_LANG_ALIAS.$pms_pages[$id]['alias']; ?>"><?php echo $pms_pages[$id]['name']; ?></a></li>
                <?php
            }else{ ?>
                <li><span><?php echo $pms_pages[$id]['name']; ?></span>
                <?php
            }
        } ?>
    </ul>
    <?php
} ?>
