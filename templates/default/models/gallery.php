<?php
if($article_alias == "") pms_err404();

if($pms_article_id > 0){
    
    $pms_article_id = $article['id'];
    $title_tag = $article['title']." - ".$title_tag;
    $page_title = $article['title'];
    $page_subtitle = $article['subtitle'];
    $page_alias = $article['alias'];

    if($article['comment'] == 1){
        $result_comment = $pms_db->query("SELECT * FROM pm_comment WHERE id_article = ".$pms_article_id." AND checked = 1 ORDER BY add_date DESC");
        if($result_comment !== false)
            $nb_comments = $pms_db->last_row_count();
    }
}else pms_err404();

pms_check_URI(DOCBASE.$page_alias);

/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
$pms_javascripts[] = '//unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js';

$pms_stylesheets[] = array("file" => DOCBASE."js/plugins/lazyloader/lazyloader.css", "media" => "all");
$pms_javascripts[] = DOCBASE."js/plugins/lazyloader/lazyloader.js";

require(pms_getFromTemplate("common/header.php", false)); ?>

<article id="page">
    <?php include(pms_getFromTemplate("common/page_header.php", false)); ?>
    
    <div id="content" class="pt10 pb30">
        <div class="container">
            <div class="row">
                <?php
                $lz_offset = 1;
                $lz_limit = 9;
                $lz_pages = 0;
                $num_records = 0;
                $result = $pms_db->query("SELECT count(*) FROM pm_article_file WHERE id_item = ".$pms_article_id." AND checked = 1 AND lang = ".PMS_DEFAULT_LANG." AND type = 'image' AND file != ''");
                if($result !== false){
                    $num_records = $result->fetchColumn(0);
                    $lz_pages = ceil($num_records/$lz_limit);
                }
                if($num_records > 0){ ?>
                    <div class="isotopeWrapper clearfix isotope popup-gallery lazy-wrapper" data-loader="<?php echo pms_getFromTemplate("common/get_images.php"); ?>" data-mode="click" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $pms_texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="article_id=<?php echo $pms_article_id; ?>">
                        <?php include(pms_getFromTemplate("common/get_images.php", false)); ?>
                    </div>
                    <?php
                } ?>
                
            </div>
        </div>
    </div>
</article>
