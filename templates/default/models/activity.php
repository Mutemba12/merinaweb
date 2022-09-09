<?php
if($article_alias == "") pms_err404();

$result = $pms_db->query("SELECT * FROM pm_activity WHERE checked = 1 AND lang = ".PMS_LANG_ID." AND alias = ".$pms_db->quote($article_alias));
if($result !== false && $pms_db->last_row_count() > 0){
    
    $activity = $result->fetch(PDO::FETCH_ASSOC);
    
    $activity_id = $activity['id'];
    $pms_article_id = $activity_id;
    $title_tag = $activity['title']." - ".$title_tag;
    $page_title = $activity['title'];
    $page_subtitle = "";
    $page_alias = $pms_pages[$pms_page_id]['alias']."/".pms_text_format($activity['alias']);
    
    $result_activity_file = $pms_db->query("SELECT * FROM pm_activity_file WHERE id_item = ".$activity_id." AND checked = 1 AND lang = ".PMS_DEFAULT_LANG." AND type = 'image' AND file != '' ORDER BY `rank` LIMIT 1");
    if($result_activity_file !== false && $pms_db->last_row_count() > 0){
        
        $row = $result_activity_file->fetch();
        
        $file_id = $row['id'];
        $filename = $row['file'];
        
        if(is_file(SYSBASE."medias/activity/medium/".$file_id."/".$filename))
            $page_img = pms_getUrl(true).DOCBASE."medias/activity/medium/".$file_id."/".$filename;
    }
    
}else pms_err404();

pms_check_URI(DOCBASE.$page_alias);

/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */

$pms_javascripts[] = DOCBASE.'js/plugins/sharrre/jquery.sharrre.min.js';

$pms_javascripts[] = DOCBASE."js/plugins/jquery.event.calendar/js/jquery.event.calendar.js";
$pms_javascripts[] = DOCBASE."js/plugins/jquery.event.calendar/js/languages/jquery.event.calendar.".PMS_LANG_TAG.".js";
$pms_stylesheets[] = array("file" => DOCBASE."js/plugins/jquery.event.calendar/css/jquery.event.calendar.css", "media" => "all");

$pms_stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', 'media' => 'all');
$pms_stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', 'media' => 'all');
$pms_javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';

$pms_stylesheets[] = array('file' => '//cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.0.7/css/star-rating.css', 'media' => 'all');
$pms_javascripts[] = '//cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.0.7/js/star-rating.js';

require(pms_getFromTemplate("common/send_comment.php", false));

require(pms_getFromTemplate("common/header.php", false)); ?>

<article id="page">
    <?php include(pms_getFromTemplate("common/page_header.php", false)); ?>
    
    <div id="content" class="pt30 pb30">
        <div class="container">
            
            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
            
            <div class="row">
                <div class="col-md-8 mb20">
                    <div class="row mb10">
                        <div class="col-sm-8">
                            <h1 class="mb0">
                                <?php echo $activity['title']; ?>
                                <br><small><?php echo $activity['subtitle']; ?></small>
                            </h1>
                            <?php
                            $result_rating = $pms_db->query("SELECT count(*) as count_rating, AVG(rating) as avg_rating FROM pm_comment WHERE item_type = 'activity' AND id_item = ".$activity_id." AND checked = 1 AND rating > 0 AND rating <= 5");
                            if($result_rating !== false && $pms_db->last_row_count() == 1){
                                $row = $result_rating->fetch();
                                $activity_rating = $row['avg_rating'];
                                $count_rating = $row['count_rating'];
                                
                                if($activity_rating > 0 && $activity_rating <= 5){ ?>
                                
                                    <input type="hidden" class="rating pull-left" value="<?php echo $activity_rating; ?>" data-rtl="<?php echo (PMS_RTL_DIR) ? true : false; ?>" data-size="xs" readonly="true" data-default-caption="<?php echo $count_rating." ".$pms_texts['RATINGS']; ?>" data-show-caption="true">
                                    <?php
                                }
                            } ?>
                            <div class="clearfix"></div>
                            <p class="text-muted">
                                <i class="fas fa-fw fa-clock"></i> <?php echo $pms_texts['DURATION']; ?> <?php echo $activity['duration']." ".$pms_texts[mb_strtoupper($activity['duration_unit'])]; ?>
                            </p>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div class="price text-primary">
                                <?php
                                $min_price = 0;
                                $result_rate = $pms_db->query("
                                    SELECT DISTINCT(price)
                                    FROM pm_activity_session
                                    WHERE id_activity = ".$activity_id."
                                        AND price IN(SELECT MIN(price) FROM pm_activity_session WHERE id_activity = ".$activity_id.")
                                    ORDER BY price
                                    LIMIT 1");
                                if($result_rate !== false && $pms_db->last_row_count() == 1){
                                    $row = $result_rate->fetch();
                                    $price = $row['price'];
                                    if($price > 0) $min_price = $price;
                                }
                                if($min_price > 0){
                                    echo $pms_texts['FROM_PRICE']; ?>
                                    <span itemprop="priceRange">
                                        <?php echo pms_formatPrice($min_price*PMS_CURRENCY_RATE); ?>
                                    </span>
                                    / <?php echo $pms_texts['PERSON'];
                                } ?>
                            </div>
                            <form action="<?php echo DOCBASE.$pms_sys_pages['booking-activities']['alias']; ?>" method="post">
								<input type="hidden" name="activity_id" value="<?php echo $activity_id; ?>">
								<button type="submit" class="btn btn-success" name="book-activity"><?php echo $pms_texts['BOOK']; ?></button>
                            </form>
                        </div>
                    </div>
                    <div class="row mb10">
                        <div class="col-md-12">
                            <div class="owl-carousel owlWrapper" data-items="1" data-autoplay="true" data-dots="true" data-nav="false" data-rtl="<?php echo (PMS_RTL_DIR) ? "true" : "false"; ?>">
                                <?php
                                $result_activity_file = $pms_db->query("SELECT * FROM pm_activity_file WHERE id_item = ".$activity_id." AND checked = 1 AND lang = ".PMS_DEFAULT_LANG." AND type = 'image' AND file != '' ORDER BY `rank`");
                                if($result_activity_file !== false){
                                    
                                    foreach($result_activity_file as $i => $row){
                                    
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        
                                        $realpath = SYSBASE."medias/activity/big/".$file_id."/".$filename;
                                        $thumbpath = DOCBASE."medias/activity/big/".$file_id."/".$filename;
                                        
                                        if(is_file($realpath)){ ?>
                                            <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>" class="img-responsive" style="max-height:600px;"/>
                                            <?php
                                        }
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb10">
                        <div class="col-md-12" itemprop="description">
                            <?php echo $activity['descr']; ?>
                        </div>
                    </div>
                    <div class="row mt30">
                        <div class="col-md-12">
                            <?php
                            $nb_comments = 0;
                            $item_type = "activity";
                            $item_id = $activity_id;
                            $allow_comment = PMS_ALLOW_COMMENTS;
                            $allow_rating = PMS_ALLOW_RATINGS;
                            if($allow_comment == 1){
                                $result_comment = $pms_db->query("SELECT * FROM pm_comment WHERE id_item = ".$item_id." AND item_type = '".$item_type."' AND checked = 1 ORDER BY add_date DESC");
                                if($result_comment !== false)
                                    $nb_comments = $pms_db->last_row_count();
                            }
                            include(pms_getFromTemplate("common/comments.php", false)); ?>
                        </div>
                    </div>
                </div>
                <aside class="col-md-4 mb20">
                    <div class="boxed">
                        <div itemscope itemtype="http://schema.org/Corporation">
                            <h3 itemprop="name"><?php echo $activity['title']; ?></h3>
                        </div>
                        <script type="text/javascript">
                            var pms_locations = [
                                ['<?php echo $activity['title']; ?>', '', '<?php echo $activity['lat']; ?>', '<?php echo $activity['lng']; ?>']
                            ];
                        </script>
                        <div id="mapWrapper" class="mb30" data-marker="<?php echo pms_getFromTemplate("images/marker.png"); ?>" data-api_key="<?php echo PMS_GMAPS_API_KEY; ?>"></div>
                        <?php
                        $id_activity = 0;
                        $result_rate = $pms_db->prepare("
                            SELECT DISTINCT(price)
                            FROM pm_activity_session
                            WHERE id_activity = :id_activity
                                AND price IN(SELECT MIN(price) FROM pm_activity_session WHERE id_activity = :id_activity)
                            ORDER BY price
                            LIMIT 1");
                        $result_rate->bindParam(":id_activity", $id_activity);
                        
                        $result_activity_file = $pms_db->prepare("SELECT * FROM pm_activity_file WHERE id_item = :id_activity AND checked = 1 AND lang = ".PMS_LANG_ID." AND type = 'image' AND file != '' ORDER BY `rank`");
                        $result_activity_file->bindParam(":id_activity", $id_activity, PDO::PARAM_STR);
                
                        $result_activity = $pms_db->query("SELECT * FROM pm_activity WHERE id != ".$activity_id." AND checked = 1 AND lang = ".PMS_LANG_ID." ORDER BY `rank` LIMIT 10", PDO::FETCH_ASSOC);
                        if($result_activity !== false && $pms_db->last_row_count() > 0){
                            foreach($result_activity as $i => $row){
                                $id_activity = $row['id'];
                                $activity_title = $row['title'];
                                $activity_subtitle = $row['subtitle'];
                                $activity_descr = $row['descr'];
                                $activity_alias = $row['alias'];
                                $duration_value = $row['duration'];
                                $duration_unit = $row['duration_unit'];
                                $activity_price = $row['price']; ?>
                                
                                <a href="<?php echo DOCBASE.$page['alias']."/".pms_text_format($activity_alias); ?>">
                                    <div class="row">
                                        <div class="col-xs-4 mb20">
                                            <?php
                                            $result_activity_file->execute();
                                            if($result_activity_file !== false && $pms_db->last_row_count() > 0){
                                                $row = $result_activity_file->fetch(PDO::FETCH_ASSOC);
                                                
                                                $file_id = $row['id'];
                                                $filename = $row['file'];
                                                $label = $row['label'];
                                                
                                                $realpath = SYSBASE."medias/activity/small/".$file_id."/".$filename;
                                                $thumbpath = DOCBASE."medias/activity/small/".$file_id."/".$filename;
                                                    
                                                if(is_file($realpath)){ ?>
                                                    <div class="img-container sm">
                                                        <img alt="" src="<?php echo $thumbpath; ?>">
                                                    </div>
                                                    <?php
                                                }
                                            } ?>
                                        </div>
                                        <div class="col-xs-8">
                                            <h3 class="mb0"><?php echo $activity_title; ?></h3>
                                            <?php
                                            if($activity_subtitle != ""){ ?>
                                                <h4 class="mb0"><?php echo $activity_subtitle; ?></h4>
                                                <?php
                                            } ?>
                                            <p class="text-muted">
                                                <i class="fas fa-fw fa-clock"></i> <?php echo $duration_value." ".$pms_texts[mb_strtoupper($duration_unit)]; ?>
                                            </p>
                                            <?php
                                            $min_price = $activity_price;
                                            $result_rate->execute();
                                            if($result_rate !== false && $pms_db->last_row_count() == 1){
                                                $row = $result_rate->fetch();
                                                $price = $row['price'];
                                                if($price > 0) $min_price = $price;
                                            } ?>
                                            <div class="price text-primary">
                                                <?php echo $pms_texts['FROM_PRICE']; ?>
                                                <span itemprop="priceRange">
                                                    <?php echo pms_formatPrice($min_price*PMS_CURRENCY_RATE); ?>
                                                </span>
                                                / <?php echo $pms_texts['PERSON']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            } ?>
                            <?php
                        } ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</article>
