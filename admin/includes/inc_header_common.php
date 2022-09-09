<?php debug_backtrace() || die ('Direct access not permitted'); ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>
    Pandao CMS
    <?php
    echo TITLE_ELEMENT;
    if(defined('PMS_SITE_TITLE')) echo ' | '.PMS_SITE_TITLE; ?>
</title>

<?php
if(defined('PMS_TEMPLATE')){ ?>
    <link rel="icon" type="image/png" href="<?php echo DOCBASE; ?>templates/<?php echo PMS_TEMPLATE; ?>/images/favicon.png">
    <?php
} ?>
    
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,700">
<link rel="stylesheet" href="<?php echo DOCBASE; ?>common/css/shortcodes.css">
<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo DOCBASE.PMS_ADMIN_FOLDER; ?>/css/layout.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">

<script src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="<?php echo DOCBASE; ?>common/js/modernizr-2.6.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//use.fontawesome.com/releases/v5.15.3/js/all.js" integrity="sha384-haqrlim99xjfMxRP6EWtafs0sB1WKcMdynwZleuUSwJR0mDeRYbhtY+KPMr+JL6f" crossorigin="anonymous"></script>
<script src="<?php echo DOCBASE; ?>common/js/custom.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

<script>
    $(function(){
        <?php
        if(isset($_SESSION['msg_error']) && isset($_SESSION['msg_success']) && isset($_SESSION['msg_notice'])){
            
            if(!is_array($_SESSION['msg_error'])) $_SESSION['msg_error'] = array();
            if(!is_array($_SESSION['msg_success'])) $_SESSION['msg_success'] = array();
            if(!is_array($_SESSION['msg_notice'])) $_SESSION['msg_notice'] = array();
            
            $_SESSION['msg_error'] = array_unique($_SESSION['msg_error']);
            $_SESSION['msg_success'] = array_unique($_SESSION['msg_success']);
            $_SESSION['msg_notice'] = array_unique($_SESSION['msg_notice']); ?>
            
            var msg_error = '<?php echo str_replace(addslashes("\n"), "\n", addslashes(implode('<br>', $_SESSION['msg_error']))); ?>';
            var msg_success = '<?php echo str_replace(addslashes("\n"), "\n", addslashes(implode('<br>', $_SESSION['msg_success']))); ?>';
            var msg_notice = '<?php echo str_replace(addslashes("\n"), "\n", addslashes(implode('<br>', $_SESSION['msg_notice']))); ?>';
            
            var button_close = '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>';
            if(msg_error != '') $('.alert-container .alert-danger').html(msg_error+button_close).show();
            if(msg_success != '') $('.alert-container .alert-success').html(msg_success+button_close).show();
            if(msg_notice != '') $('.alert-container .alert-warning').html(msg_notice+button_close).show();
            <?php
        } ?>
        
        $('[data-toggle="tooltip"]').tooltip();
        
        $('select[data-filter]').each(function(){
            var target = $(this);
            var currval = $(this).val();
            var curropt = $('option[value="'+currval+'"]', target);
            var input = $('select').filter('<?php if(defined('MODULE')) : ?>[name^="<?php echo MODULE; ?>_'+target.attr('data-filter')+'"],<?php endif; ?>[name="'+target.attr('data-filter')+'"]');
            input.on('change', function(){
                var val = $(this).val();
                $('option[value!=""]', target).hide().prop('selected', false);
                $('option[rel="'+val+'"]', target).show();
                if(curropt.attr('rel') == val) curropt.prop('selected', true);
            });
            input.trigger('change');
        });
        
        $(window).on('resize', function(){
            var h = $(this).height() - 50;
            $('.side-nav').css('max-height', h);
        });
        $(window).trigger('resize');
    })
</script>
