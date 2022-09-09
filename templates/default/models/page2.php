<?php
/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
$pms_javascripts[] = '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js';

$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
$pms_javascripts[] = DOCBASE.'js/plugins/lazyloader/lazyloader.js';

$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/star-rating/css/star-rating.min.css', 'media' => 'all');
$pms_javascripts[] = DOCBASE.'js/plugins/star-rating/js/star-rating.min.js';

require(pms_getFromTemplate('common/send_comment.php', false));

require(pms_getFromTemplate('common/header.php', false)); ?>

<section id="page">
    
    <?php include(pms_getFromTemplate('common/page_header.php', false)); ?>
    
    <div id="content" class="pt30 pb20">
        <div class="container" itemprop="text">

            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
            
            <div class="row">
                <?php
                $widgetsLeft = pms_getWidgets('left', $pms_page_id);
                $widgetsRight = pms_getWidgets('right', $pms_page_id);
                
                if(!empty($widgetsLeft)){ ?>
                    <div class="col-sm-3">
                        <?php pms_displayWidgets('left', $pms_page_id); ?>
                    </div>
                    <?php
                } ?>
                
                <div class="col-sm-<?php if(!empty($widgetsLeft) && !empty($widgetsRight)) echo 6; elseif(!empty($widgetsLeft) || !empty($widgetsRight)) echo 9; else echo 12; ?>">
                    <?php echo $page['text']; ?>
                </div>
                
                <?php
                if(!empty($widgetsRight)){ ?>
                    <div class="col-sm-3">
                        <?php pms_displayWidgets('right', $pms_page_id); ?>
                    </div>
                    <?php
                } ?>
            </div>

            <div class="row">
                <?php
                $lz_offset = 1;
                $lz_limit = 9;
                $lz_pages = 0;
                $num_records = 0;
				$result = $pms_db->query('SELECT count(*)
									FROM pm_article
									WHERE id_page = '.$pms_page_id.'
										AND checked = 1
										AND (publish_date IS NULL || publish_date <= '.time().')
										AND (unpublish_date IS NULL || unpublish_date > '.time().')
										AND lang = '.PMS_LANG_ID.'
										AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.PMS_LANG_ID.'(,|$)\')
										AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.PMS_LANG_ID.'(,|$)\')');
				if($result !== false){
                    $num_records = $result->fetchColumn(0);
                    $lz_pages = ceil($num_records/$lz_limit);
                }
                if($num_records > 0){
                    
                    $result_tag = $pms_db->query('SELECT * FROM pm_tag WHERE pages REGEXP \'(^|,)'.$pms_page_id.'(,|$)\' AND checked = 1 AND lang = '.PMS_LANG_ID.' ORDER BY `rank`');
                    if($result_tag !== false){
                        $nb_tags = $pms_db->last_row_count();
                        
                        if($nb_tags > 0){ ?>
                    
                            <nav id="filter" class="text-center mt20">
                                <div class="btn-group">
                                    <a href="" class="btn btn-default" data-filter="*"><?php echo $pms_texts['ALL']; ?></a>
                                    <?php
                                    foreach($result_tag as $i => $row){
                                        $tag_id = $row['id'];
                                        $tag_value = $row['value']; ?>
                                        
                                        <a href="" class="btn btn-default" data-filter=".tag<?php echo $tag_id; ?>"><?php echo $tag_value; ?></a>
                                        
                                        <?php
                                    } ?>
                                </div>
                            </nav>
                            <?php
                        }
                    } ?>
                    
                    <div class="isotopeWrapper clearfix isotope lazy-wrapper" data-loader="<?php echo pms_getFromTemplate('common/get_articles_popup.php'); ?>" data-mode="click" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $pms_texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="page_id=<?php echo $pms_page_id; ?>&page_alias=<?php echo $page['alias']; ?>">
                        <?php include(pms_getFromTemplate('common/get_articles_popup.php', false)); ?>
                    </div>
                    <?php
                } ?>
            </div>
            
            <?php
            $nb_comments = 0;
            $item_type = 'page';
            $item_id = $pms_page_id;
            $allow_comment = $page['comment'];
            $allow_rating = $page['rating'];
            if($allow_comment == 1){
                $result_comment = $pms_db->query('SELECT * FROM pm_comment WHERE id_item = '.$item_id.' AND item_type = \''.$item_type.'\' AND checked = 1 ORDER BY add_date DESC');
                if($result_comment !== false)
                    $nb_comments = $pms_db->last_row_count();
            }
            include(pms_getFromTemplate('common/comments.php', false)); ?>
        </div>
    </div>
</section>
