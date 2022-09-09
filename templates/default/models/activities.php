<?php
$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
$pms_javascripts[] = '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js';

$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
$pms_javascripts[] = DOCBASE.'js/plugins/lazyloader/lazyloader.js';

require(pms_getFromTemplate('common/header.php', false)); ?>

<section id="page">
    
    <?php include(pms_getFromTemplate('common/page_header.php', false)); ?>
    
    <div id="content" class="pt30 pb20">
        <div class="container">
            <div class="row">
                <?php
                if($page['text'] != ''){ ?>
                    <div class="col-md-12"><?php echo $page['text']; ?></div>
                    <?php
                } ?>
            </div>
            <div class="row">
                <?php
                $lz_offset = 1;
                $lz_limit = 9;
                $lz_pages = 0;
                $num_records = 0;
                $result = $pms_db->query('SELECT count(*) FROM pm_activity WHERE checked = 1 AND lang = '.PMS_LANG_ID);
                if($result !== false){
                    $num_records = $result->fetchColumn(0);
                    $lz_pages = ceil($num_records/$lz_limit);
                }
                if($num_records > 0){ ?>
                    <div class="isotopeWrapper clearfix isotope lazy-wrapper" data-loader="<?php echo pms_getFromTemplate('common/get_activities.php'); ?>" data-mode="click" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $pms_texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="page_id=<?php echo $pms_page_id; ?>&page_alias=<?php echo $page['alias']; ?>">
                        <?php include(pms_getFromTemplate('common/get_activities.php', false)); ?>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</section>

