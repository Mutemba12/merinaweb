<?php debug_backtrace() || die ("Direct access not permitted"); ?>
<footer>
    <section id="mainFooter">
        <div class="container" id="footer">
            <div class="row">
                <div class="col-md-4">
                    <?php pms_displayWidgets("footer_col_1", $pms_page_id); ?>
                </div>
                <div class="col-md-4">
                    <?php pms_displayWidgets("footer_col_2", $pms_page_id); ?>
                </div>
                <div class="col-md-4">
                    <?php pms_displayWidgets("footer_col_3", $pms_page_id); ?>
                </div>
            </div>
        </div>
    </section>
    <div id="footerRights">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>
                        &copy; <?php echo date("Y"); ?>
                        <?php echo PMS_OWNER." ".$pms_texts['ALL_RIGHTS_RESERVED']; ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="text-right">
                        <a href="<?php echo DOCBASE; ?>feed/" target="_blank" title="<?php echo $pms_texts['RSS_FEED']; ?>"><i class="fas fa-fw fa-rss"></i></a>
                        <?php
                        foreach($pms_menus['footer'] as $nav_id => $nav){ ?>
                            <a href="<?php echo $nav['href']; ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
                            &nbsp;&nbsp;
                            <?php
                        } ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
$result_popup = $pms_db->query('SELECT * FROM pm_popup
                            WHERE lang = '.PMS_LANG_ID.'
                                AND checked = 1 
                                AND (publish_date IS NULL || publish_date <= '.time().')
                                AND (unpublish_date IS NULL || unpublish_date > '.time().')
                                AND (allpages = 1 OR pages REGEXP \'(^|,)'.$pms_page_id.'(,|$)\')
                            LIMIT 1');
if($result_popup !== false && $pms_db->last_row_count() > 0){
    $row = $result_popup->fetch();
    
    $id_popup = $row['id'];
    
    if(!isset($_SESSION['popup_'.$id_popup])){
        $popup_content = $row['content'];
        $popup_bg = $row['background'];
        
        $_SESSION['popup_'.$id_popup] = 1; ?>
        
        <a class="popup-modal hide" href="#popup-<?php echo $id_popup; ?>"></a>
        
        <div id="popup-<?php echo $id_popup; ?>" class="white-popup-block mfp-hide"<?php if(!empty($popup_bg)) echo ' style="background-color:'.$popup_bg.';"'; ?>>
            <div class="fluid-container">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $popup_content; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} ?>

<?php
if(isset($_SESSION['book'])
 && $pms_page_id != $pms_sys_pages['booking-activities']['id']
 && $pms_page_id != $pms_sys_pages['details']['id']
 && $pms_page_id != $pms_sys_pages['summary']['id']
 && $pms_page_id != $pms_sys_pages['payment']['id']
 && isset($_SESSION['book']['rooms'])
 && count($_SESSION['book']['rooms']) > 0){ ?>
	<div id="booking-cart" class="alert alert-dismissible">
        <form method="post" class="ajax-form">
            <a href="#" class="close sendAjaxForm" data-action="<?php echo pms_getFromTemplate('common/cancel_booking.php'); ?>" data-dismiss="alert" aria-label="close">&times;</a>
            <?php
            if(isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0){
                $rooms = array_keys($_SESSION['book']['rooms']);
                $id_room = array_shift($rooms);
                $result_room_file = $pms_db->query('SELECT * FROM pm_room_file WHERE id_item = '.$id_room.' AND checked = 1 AND lang = '.PMS_LANG_ID.' AND type = \'image\' AND file != \'\' ORDER BY `rank`');
                if($result_room_file !== false && $pms_db->last_row_count() > 0){
                    $row = $result_room_file->fetch(PDO::FETCH_ASSOC);

                    $file_id = $row['id'];
                    $filename = $row['file'];
                    $label = $row['label'];

                    $realpath = SYSBASE.'medias/room/small/'.$file_id.'/'.$filename;
                    $thumbpath = DOCBASE.'medias/room/small/'.$file_id.'/'.$filename;
                    $zoompath = DOCBASE.'medias/room/big/'.$file_id.'/'.$filename;

                    if(is_file($realpath)){
                        $s = getimagesize($realpath); ?>
                        <div class="img-container sm pull-left">
                            <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>">
                        </div>
                        <?php
                    }
                }
            }
            $step = (isset($_SESSION['book']['step'])) ? $_SESSION['book']['step'] : 'details'; ?>
            <a href="<?php echo DOCBASE.$pms_sys_pages[$step]['alias']; ?>" class="alert-link"><?php echo $pms_texts['COMPLETE_YOUR_BOOKING']; ?></a><br>
            <small><?php echo gmstrftime(PMS_DATE_FORMAT, $_SESSION['book']['from_date']); ?> <i class="fas fa-fw fa-arrow-right"></i> <?php echo gmstrftime(PMS_DATE_FORMAT, $_SESSION['book']['to_date']); ?></small><br>
			<?php if(isset($_SESSION['book']['num_rooms'])) echo $_SESSION['book']['num_rooms'].' '.pms_getAltText($pms_texts['ROOM'], $pms_texts['ROOMS'], $_SESSION['book']['num_rooms']); ?>
            <b><?php if($_SESSION['book']['total'] > 0) echo ' - '.pms_formatPrice($_SESSION['book']['total']); ?></b>
            <div class="clearfix"></div>
        </form>
	</div>
	<?php
} ?>

<a href="#" id="toTop"><i class="fas fa-fw fa-angle-up"></i></a>

<!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<script src="//rawgit.com/tuupola/jquery_lazyload/2.x/lazyload.min.js"></script>
<script src="<?php echo DOCBASE; ?>common/js/modernizr-2.6.1.min.js"></script>

<script>
    Modernizr.load({
        load : [
            '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js',
            '//code.jquery.com/ui/1.12.1/jquery-ui.js',
            <?php if(PMS_LANG_TAG != "en") : ?>'//rawgit.com/jquery/jquery-ui/master/ui/i18n/datepicker-<?php echo PMS_LANG_TAG; ?>.js',<?php endif; ?>
            '//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js',
            '//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js',
            
            //Javascripts required by the current model
            <?php if(isset($pms_javascripts)) foreach($pms_javascripts as $javascript) echo "'".$javascript."',\n"; ?>
            
            '//unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js',
            '<?php echo DOCBASE; ?>js/plugins/imagefill/js/jquery-imagefill.js',
            '<?php echo DOCBASE; ?>js/plugins/toucheeffect/toucheffects.js',
            '//use.fontawesome.com/releases/v5.15.3/js/all.js'
        ],
        complete : function(){
            Modernizr.load({
                load : [
                    '<?php echo DOCBASE; ?>common/js/custom.js',
                    '<?php echo DOCBASE; ?>js/custom.js'
                ]
            });
        }
    });
    
    $(function(){
		<?php
		if(PMS_ENABLE_ICAL && PMS_ENABLE_AUTO_ICAL_SYNC){ ?>
			$.ajax({
				url: '<?php echo DOCBASE; ?>includes/icalendar/ical_import.php',
				type: 'POST',
				data: 'ical_sync_mode=auto'
			});
			<?php
		}
        if(isset($msg_error) && $msg_error != ""){
            $str = mb_ereg_replace("(\r\n|\n|\r)","'+\n'",nl2br($msg_error));
            if(!empty($str)){ ?>
                $('.alert-danger').html('<?php echo $str; ?>').slideDown();
                <?php
            }
        }
        if(isset($msg_success) && $msg_success != ""){
            $str = mb_ereg_replace("(\r\n|\n|\r)","'+\n'",nl2br($msg_success));
            if(!empty($str)){ ?>
                $('.alert-success').html('<?php echo $str; ?>').slideDown();
                <?php
            }
        }
        if(isset($field_notice) && !empty($field_notice))
            foreach($field_notice as $field => $notice) echo "$('.field-notice[rel=\"".$field."\"]').html('".$notice."').fadeIn('slow').parent().addClass('alert alert-danger');\n"; ?>
    });
</script>
<script src='https://www.google.com/recaptcha/api.js?hl=<?php echo PMS_LANG_TAG; ?>'></script>
</body>
</html>
