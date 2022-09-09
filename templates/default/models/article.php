<?php
if($article_alias == '') pms_err404();

if($pms_article_id > 0){
    
    $title_tag = $article['title'].' - '.$title_tag;
    $page_title = $article['title'];
    $page_subtitle = $article['subtitle'];
    $page_alias = $article['alias'];
    $publish_date = $article['publish_date'];
    $edit_date = $article['edit_date'];
    
    if(is_null($publish_date)) $publish_date = $article['add_date'];
    if(is_null($edit_date)) $edit_date = $publish_date;
    
    $result_article_file = $pms_db->query('SELECT * FROM pm_article_file WHERE id_item = '.$pms_article_id.' AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank` LIMIT 1');
    if($result_article_file !== false && $pms_db->last_row_count() > 0){
        
        $row = $result_article_file->fetch();
        
        $file_id = $row['id'];
        $filename = $row['file'];
        
        if(is_file(SYSBASE.'medias/article/medium/'.$file_id.'/'.$filename))
            $page_img = pms_getUrl(true).DOCBASE.'medias/article/medium/'.$file_id.'/'.$filename;
    }
    
    if(!empty($article['tags'])){
        $result_tag = $pms_db->query('SELECT * FROM pm_tag WHERE id IN ('.$article['tags'].') AND checked = 1 AND lang = '.PMS_LANG_ID.' ORDER BY `rank`');
        if($result_tag !== false){
            $nb_tags = $pms_db->last_row_count();
            
            $article_tags = '';
            foreach($result_tag as $i => $row){
                $tag_id = $row['id'];
                $tag_value = $row['value'];

                $article_tags .= $tag_value;
                if($i+1 < $nb_tags) $article_tags .= ', ';
            }
        }
    }
    
}else pms_err404();

pms_check_URI(DOCBASE.$page_alias);

/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$pms_javascripts[] = DOCBASE.'js/plugins/sharrre/jquery.sharrre.min.js';

$pms_stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', 'media' => 'all');
$pms_stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', 'media' => 'all');
$pms_javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js';

$pms_stylesheets[] = array('file' => '//cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.0.7/css/star-rating.css', 'media' => 'all');
$pms_javascripts[] = '//cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.0.7/js/star-rating.js';

require(pms_getFromTemplate('common/send_comment.php', false));

require(pms_getFromTemplate('common/header.php', false)); ?>

<article id="page">
    <?php include(pms_getFromTemplate('common/page_header.php', false)); ?>
    
    <div id="content" class="pt30 pb30">
        <div class="container">

            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
            
            <div class="row">
                <div class="col-sm-6 mb10">
                    <?php
                    $nb_comments = 0;
                    $item_type = 'article';
                    $item_id = $pms_article_id;
                    $allow_comment = $article['comment'];
                    $allow_rating = $article['rating'];
                    if($allow_comment == 1){
                        $result_comment = $pms_db->query('SELECT * FROM pm_comment WHERE id_item = '.$item_id.' AND item_type = \''.$item_type.'\' AND checked = 1 ORDER BY add_date DESC');
                        if($result_comment !== false)
                            $nb_comments = $pms_db->last_row_count();
                    } ?>
                    <div class="mb10 labels" dir="ltr">
                        <span class="label label-default"><i class="fas fa-fw fa-thumbtack"></i> <?php echo (!PMS_RTL_DIR) ? strftime(PMS_DATE_FORMAT, $article['add_date']) : strftime('%F', $article['add_date']); ?></span>
                        <span class="label label-default"><i class="fas fa-fw fa-comment"></i> <?php echo $nb_comments.' '.mb_strtolower($pms_texts['COMMENTS'], 'UTF-8'); ?></span>
                        <?php
                        if(!empty($article['tags'])){
                            $result_tag = $pms_db->query('SELECT * FROM pm_tag WHERE id IN ('.$article['tags'].') AND checked = 1 AND lang = '.PMS_LANG_ID.' ORDER BY `rank`');
                            if($result_tag !== false){
                                $nb_tags = $pms_db->last_row_count();
                                
                                if($nb_tags > 0){ ?>
                                    <span class="label label-default"><i class="fas fa-fw fa-tags"></i>
                                        <?php
                                        foreach($result_tag as $i => $row){
                                            $tag_id = $row['id'];
                                            $tag_value = $row['value'];

                                            echo $tag_value;
                                            if($i+1 < $nb_tags) echo ', ';
                                        } ?>
                                    </span>
                                    <?php
                                }
                            }
                        } ?>
                    </div>
                    <?php
                    echo $article['text'];
                    
                    $short_text = pms_strtrunc(pms_rip_tags($article['text']), 100);
                    $site_url = pms_getUrl(); ?>
                   
                    <div id="twitter" data-url="<?php echo $site_url; ?>" data-text="<?php echo $short_text; ?>" data-title="Tweet"></div>
                    <div id="facebook" data-url="<?php echo $site_url; ?>" data-text="<?php echo $short_text; ?>" data-title="Like"></div>
                    <div id="pinterest" data-media="<?php if(isset($page_img)) echo $page_img; ?>" data-text="<?php echo $short_text; ?>"></div>
                
                    <div class="clearfix"></div>
                </div>
                <div class="col-sm-6">
                    <div class="owl-carousel owlWrapper owl-theme" data-items="1" data-autoplay="true" data-dots="true" data-nav="false" data-rtl="<?php echo (PMS_RTL_DIR) ? "true" : "false"; ?>">
                        <?php
                        $result_article_file = $pms_db->query('SELECT * FROM pm_article_file WHERE id_item = '.$pms_article_id.' AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank`');
                        if($result_article_file !== false){
                            
                            foreach($result_article_file as $i => $row){
                            
                                $file_id = $row['id'];
                                $filename = $row['file'];
                                $label = $row['label'];
                                
                                $realpath = SYSBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                $thumbpath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                
                                if(is_file($realpath)){ ?>
                                    <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>" class="img-responsive" style="max-height:600px;"/>
                                    <?php
                                }
                            }
                        } ?>
                    </div>
                </div>
            </div>
			<?php
			$result_article = $pms_db->query('SELECT *
										FROM pm_article
										WHERE id_page = '.$pms_page_id.'
											AND id != '.$pms_article_id.'
											AND checked = 1
											AND (publish_date IS NULL || publish_date <= '.time().')
											AND (unpublish_date IS NULL || unpublish_date > '.time().')
											AND lang = '.PMS_LANG_ID.'
											AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.PMS_LANG_ID.'(,|$)\')
											AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.PMS_LANG_ID.'(,|$)\')
										ORDER BY rand()
										LIMIT 3');
			if($result_article !== false){
				$nb_articles = $pms_db->last_row_count();
				
				if($nb_articles > 0){ ?>
					
					<div class="row mt30">
						<div class="col-md-12">
							<h2><?php echo $pms_texts['DISCOVER_ALSO']; ?></h2>
						</div>
					
						<div class="clearfix">
							<?php
							$pms_article_id = 0;
							$result_article_file = $pms_db->prepare('SELECT * FROM pm_article_file WHERE id_item = :article_id AND checked = 1 AND lang = '.PMS_DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY `rank` LIMIT 1');
							$result_article_file->bindParam(':article_id',$pms_article_id);
							foreach($result_article as $i => $row){
								$pms_article_id = $row['id'];
								$article_title = $row['title'];
								$article_alias = $row['alias'];
								$article_text = pms_strtrunc(pms_rip_tags($row['text']),190);
								$article_page = $row['id_page'];
								
								if(isset($pms_pages[$article_page])){
								
									$article_alias = DOCBASE.$pms_pages[$article_page]['alias'].'/'.pms_text_format($article_alias); ?>
									
									<article class="article-<?php echo $pms_article_id; ?> col-sm-4 articleItem" itemscope itemtype="http://schema.org/Article">
										<div class="isotopeInner matchHeight">
											<a itemprop="url" href="<?php echo $article_alias; ?>" class="moreLink">
												<?php
												if($result_article_file->execute() !== false && $pms_db->last_row_count() == 1){
													$row = $result_article_file->fetch(PDO::FETCH_ASSOC);
													
													$file_id = $row['id'];
													$filename = $row['file'];
													$label = $row['label'];
													
													$realpath = SYSBASE.'medias/article/medium/'.$file_id.'/'.$filename;
													$thumbpath = DOCBASE.'medias/article/medium/'.$file_id.'/'.$filename;
													$zoompath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
													
													if(is_file($realpath)){ ?>
														<figure class="more-link img-container md">
															<img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>">
															<span class="more-action">
															</span>
														</figure>
														<?php
													}
												} ?>
												<div class="isotopeContent">
													<h3 itemprop="name"><?php echo $article_title; ?></h3>
													<p>
														<?php echo $article_text; ?>
													</p>
												</div>
											</a>
										</div>
									</article>
									<?php
								}
							} ?>
						</div>
					</div>
					<?php
				}
			} ?>
            
            <?php include(pms_getFromTemplate('common/comments.php', false)); ?>
        </div>
    </div>
</article>
