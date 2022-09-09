<?php
/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/royalslider/royalslider.css', 'media' => 'all');
$pms_stylesheets[] = array('file' => DOCBASE.'js/plugins/royalslider/skins/minimal-white/rs-minimal-white.css', 'media' => 'all');
$pms_javascripts[] = DOCBASE.'js/plugins/royalslider/jquery.royalslider.min.js';

$pms_javascripts[] = DOCBASE.'js/plugins/live-search/jquery.liveSearch.js';

require(pms_getFromTemplate('common/header.php', false));

$slide_id = 0;
$result_slide_file = $pms_db->prepare('SELECT * FROM pm_slide_file WHERE id_item = :slide_id AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank` LIMIT 1');
$result_slide_file->bindParam('slide_id', $slide_id);

$result_slide = $pms_db->query('SELECT * FROM pm_slide WHERE id_page = '.$pms_page_id.' AND checked = 1 AND lang = '.PMS_LANG_ID.' ORDER BY `rank`', PDO::FETCH_ASSOC);
if($result_slide !== false){
	$nb_slides = $pms_db->last_row_count();
	if($nb_slides > 0){ ?>
        
        <div id="search-home-wrapper">
            <div id="search-home" class="container">
                <?php include(pms_getFromTemplate('common/search.php', false)); ?>
            </div>
        </div>
	
		<section id="sliderContainer">
            
			<div id="mainSlider" class="royalSlider rsMinW sliderContainer fullWidth clearfix fullSized">
                <?php
                foreach($result_slide as $i => $row){
                    $slide_id = $row['id'];
                    $slide_legend = $row['legend'];
                    $url_video = $row['url'];
                    $id_page = $row['id_page'];
                    
                    $result_slide_file->execute();
                    
                    if($result_slide_file !== false && $pms_db->last_row_count() == 1){
                        $row = $result_slide_file->fetch();
                        
                        $file_id = $row['id'];
                        $filename = $row['file'];
                        $label = $row['label'];
                        
                        $realpath = SYSBASE.'medias/slide/big/'.$file_id.'/'.$filename;
                        $thumbpath = DOCBASE.'medias/slide/small/'.$file_id.'/'.$filename;
                        $zoompath = DOCBASE.'medias/slide/big/'.$file_id.'/'.$filename;
                            
                        if(is_file($realpath)){ ?>
                        
                            <div class="rsContent">
                                <img class="rsImg" src="<?php echo $zoompath; ?>" alt=""<?php if($url_video != '') echo ' data-rsVideo="'.$url_video.'"'; ?>>
                                <?php
                                if($slide_legend != ''){ ?>
                                    <div class="infoBlock" data-fade-effect="" data-move-offset="10" data-move-effect="bottom" data-speed="200">
                                        <?php echo $slide_legend; ?>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                            <?php
                        }
                    }
                } ?>
            </div>
		</section>
		<?php
	}
} ?>
<section id="content" class="pt20 pb30">
    <div class="container">
        
        <?php pms_displayWidgets('before_content', $pms_page_id); ?>
        
        <div class="row">
            <div class="col-md-12 text-center mb30">
                <h1 itemprop="name">
                    <?php
                    echo $page['title'];
                    if($page['subtitle'] != ''){ ?>
                        <br><small><?php echo $page['subtitle']; ?></small>
                        <?php
                    } ?>
                </h1>
                <?php echo $page['text']; ?>
            </div>
        </div>
        
		<?php pms_displayWidgets('after_content', $pms_page_id); ?>
        
        <div class="row mb10">
            <?php
            $result_room = $pms_db->query('SELECT * FROM pm_room WHERE lang = '.PMS_LANG_ID.' AND checked = 1 AND home = 1 ORDER BY `rank`');
            if($result_room !== false){
                $nb_rooms = $pms_db->last_row_count();
                
                $room_id = 0;
                
                $result_room_file = $pms_db->prepare('SELECT * FROM pm_room_file WHERE id_item = :room_id AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank` LIMIT 1');
                $result_room_file->bindParam(':room_id',$room_id);
                
                $result_rate = $pms_db->prepare('SELECT MIN(price) as min_price FROM pm_rate WHERE id_room = :room_id');
                $result_rate->bindParam(':room_id', $room_id);
                
                foreach($result_room as $i => $row){
                    $room_id = $row['id'];
                    $room_title = $row['title'];
                    $room_subtitle = $row['subtitle'];
                    
                    $room_alias = DOCBASE.$pms_pages[9]['alias'].'/'.pms_text_format($row['alias']);
                    
                    $min_price = 0;
                    if($result_rate->execute() !== false && $pms_db->last_row_count() > 0){
                        $row = $result_rate->fetch();
                        $price = $row['min_price'];
                        if($price > 0) $min_price = $price;
                    } ?>
                    
                    <article class="col-sm-4 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                        <a itemprop="url" href="<?php echo $room_alias; ?>" class="moreLink">
                            <?php
                            if($result_room_file->execute() !== false && $pms_db->last_row_count() == 1){
                                $row = $result_room_file->fetch(PDO::FETCH_ASSOC);
                                
                                $file_id = $row['id'];
                                $filename = $row['file'];
                                $label = $row['label'];
                                
                                $realpath = SYSBASE.'medias/room/small/'.$file_id.'/'.$filename;
                                $thumbpath = DOCBASE.'medias/room/small/'.$file_id.'/'.$filename;
                                $zoompath = DOCBASE.'medias/room/big/'.$file_id.'/'.$filename;
                                
                                if(is_file($realpath)){
                                    $s = getimagesize($realpath); ?>
                                    <figure class="more-link">
                                        <div class="img-container lazyload md">
                                            <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                        </div>
                                        <div class="more-content">
                                            <h3 itemprop="name"><?php echo $room_title; ?></h3>
                                            <?php
                                            if($min_price > 0){ ?>
                                                <div class="more-descr">
                                                    <div class="price">
                                                        <?php echo $pms_texts['FROM_PRICE']; ?>
                                                        <span itemprop="priceRange">
                                                            <?php echo pms_formatPrice($min_price*PMS_CURRENCY_RATE); ?>
                                                        </span>
                                                    </div>
                                                    <small><?php echo $pms_texts['PRICE'].' / '.$pms_texts['NIGHT']; ?></small>
                                                </div>
                                                <?php
                                            } ?>
                                        </div>
                                        <div class="more-action">
                                            <div class="more-icon">
                                                <i class="fa fa-link"></i>
                                            </div>
                                        </div>
                                    </figure>
                                    <?php
                                }
                            } ?>
                        </a> 
                    </article>
                    <?php
                }
            } ?>
        </div>
    </div>
    <?php
    $activity_id = 0;
    $result_activity = $pms_db->query('SELECT * FROM pm_activity WHERE lang = '.PMS_LANG_ID.' AND checked = 1 AND home = 1 ORDER BY `rank`');
    if($result_activity !== false){
        $nb_activities = $pms_db->last_row_count();
        if($nb_activities > 0){ ?>
            <div class="hotBox mb30 mt5">
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center mt10 mb15"><?php echo $pms_texts['FIND_ACTIVITIES_AND_TOURS']; ?></h2>
                        <?php
                        $activity_id = 0;
                        $result_activity_file = $pms_db->prepare('SELECT * FROM pm_activity_file WHERE id_item = :activity_id AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank` LIMIT 1');
                        $result_activity_file->bindParam(':activity_id',$activity_id);
                        foreach($result_activity as $i => $row){
                            $activity_id = $row['id'];
                            $activity_title = $row['title'];
                            $activity_alias = $row['title'];
                            $activity_subtitle = $row['subtitle'];
                            $min_price = $row['price'];
                            
                            $activity_alias = DOCBASE.$pms_sys_pages['activities']['alias'].'/'.pms_text_format($row['alias']); ?>
                            
                            <article class="col-sm-3 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                                <a itemprop="url" href="<?php echo $activity_alias; ?>" class="moreLink">
                                    <?php
                                    if($result_activity_file->execute() !== false && $pms_db->last_row_count() > 0){
                                        $row = $result_activity_file->fetch(PDO::FETCH_ASSOC);
                                        
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        
                                        $realpath = SYSBASE.'medias/activity/small/'.$file_id.'/'.$filename;
                                        $thumbpath = DOCBASE.'medias/activity/small/'.$file_id.'/'.$filename;
                                        $zoompath = DOCBASE.'medias/activity/big/'.$file_id.'/'.$filename;
                                        
                                        if(is_file($realpath)){
                                            $s = getimagesize($realpath); ?>
                                            <figure class="more-link">
                                                <div class="img-container lazyload md">
                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                </div>
                                                <div class="more-content">
                                                    <h3 itemprop="name"><?php echo $activity_title; ?></h3>
                                                </div>
                                                <div class="more-action">
                                                    <div class="more-icon">
                                                        <i class="fa fa-link"></i>
                                                    </div>
                                                </div>
                                            </figure>
                                            <?php
                                        }
                                    } ?>
                                </a> 
                            </article>
                            <?php
                        } ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    $result_article = $pms_db->query('SELECT *
                                FROM pm_article
                                WHERE (id_page = '.$pms_page_id.' OR home = 1)
                                    AND checked = 1
                                    AND (publish_date IS NULL || publish_date <= '.time().')
                                    AND (unpublish_date IS NULL || unpublish_date > '.time().')
                                    AND lang = '.PMS_LANG_ID.'
                                    AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.PMS_LANG_ID.'(,|$)\')
                                    AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.PMS_LANG_ID.'(,|$)\')
                                ORDER BY `rank`');
    if($result_article !== false){
        $nb_articles = $pms_db->last_row_count();
        
        if($nb_articles > 0){ ?>
            <div class="container mt10">
                <div class="row">
                    <div class="clearfix">
                        <?php
                        $pms_article_id = 0;
                        $result_article_file = $pms_db->prepare('SELECT * FROM pm_article_file WHERE id_item = :article_id AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank` LIMIT 1');
                        $result_article_file->bindParam(':article_id', $pms_article_id);
                        foreach($result_article as $i => $row){
                            $pms_article_id = $row['id'];
                            $article_title = $row['title'];
                            $article_alias = $row['alias'];
                            $char_limit = ($i == 0) ? 1200 : 500;
                            $article_text = pms_strtrunc(pms_rip_tags($row['text'], '<p><br>'), $char_limit, true, '');
                            $article_page = $row['id_page'];
                            
                            if(isset($pms_pages[$article_page])){
                            
                                $article_alias = (empty($article_url)) ? DOCBASE.$pms_pages[$article_page]['alias'].'/'.pms_text_format($article_alias) : $article_url;
                                $target = (strpos($article_alias, 'http') !== false) ? '_blank' : '_self';
                                if(strpos($article_alias, pms_getUrl(true)) !== false) $target = '_self'; ?>
                                                                
                                <article id="article-<?php echo $pms_article_id; ?>" class="mb20 col-sm-<?php echo ($i == 0) ? 12 : 4; ?>" itemscope itemtype="http://schema.org/Article">
                                    <div class="row">
                                        <a itemprop="url" href="<?php echo $article_alias; ?>" target="<?php echo $target; ?>" class="moreLink">
                                            <div class="col-sm-<?php echo ($i == 0) ? 8 : 12; ?> mb20">
                                                <?php
                                                if($result_article_file->execute() !== false && $pms_db->last_row_count() == 1){
                                                    $row = $result_article_file->fetch(PDO::FETCH_ASSOC);
                                                    
                                                    $file_id = $row['id'];
                                                    $filename = $row['file'];
                                                    $label = $row['label'];
                                                    
                                                    $realpath = SYSBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                    $thumbpath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                    $zoompath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                    
                                                    if(is_file($realpath)){
                                                        $s = getimagesize($realpath); ?>
                                                        <figure class="more-link">
                                                            <div class="img-container lazyload xl">
                                                                <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                            </div>
                                                            <div class="more-action">
                                                                <div class="more-icon">
                                                                    <i class="fa fa-link"></i>
                                                                </div>
                                                            </div>
                                                        </figure>
                                                        <?php
                                                    }
                                                } ?>
                                            </div>
                                            <div class="col-sm-<?php echo ($i == 0) ? 4 : 12; ?>">
                                                <div class="text-overflow">
                                                    <h3 itemprop="name"><?php echo $article_title; ?></h3>
                                                    <?php echo $article_text; ?>
                                                    <div class="more-btn">
                                                        <span class="btn btn-primary"><?php echo $pms_texts['READMORE']; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </article>
                                <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } ?>
    <?php pms_displayWidgets('full_after_content', $pms_page_id); ?>
</section>
