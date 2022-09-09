<?php
debug_backtrace() || die ("Direct access not permitted");
require_once("inc_header_common.php"); ?>
<script>
    $(function(){
        'use strict';
        $('select.select-url').on('change', function(){
            if($(this).val() != '') document.location.href = $(this).val();
        });
        $('.selectall').on('click', function(){
            $('.checkitem').prop('checked', this.checked);
        });
        $('.checkitem').on('click', function(){
            if($('.checkitem').length == $('.checkitem:checked').length)
                $('.selectall').prop('checked', true);
            else
                $('.selectall').removeAttr('checked');
        });
        $('select[name="multiple_actions"]').on('change', function(){
            if(($(this).val() == 'delete_multi' && confirm('<?php echo $pms_texts['DELETE_CONFIRM1']." ".$pms_texts['LOOSE_DATAS']; ?>')) || ($(this).val() != 'delete_multi' && $(this).val() != ''))
                $('#form').attr('action','index.php?view=list&csrf_token=<?php echo $csrf_token; ?>&action='+$(this).val()).trigger('submit');
        });
    });
</script>
<?php
if(RANKING){ ?>
    <script src="<?php echo DOCBASE.PMS_ADMIN_FOLDER; ?>/js/jquery.tablednd.js"></script>
    <script>
        $(function(){
            'use strict';
            $(window).mouseup(function(){
                $('.listing_content').each(function(i){
                    $(this).removeClass('tr'+((i+1)%2)).addClass('tr'+(i%2));
                });
            });
            <?php
            if($_SESSION['user']['type'] == "administrator"){ ?>
                $('#listing_base').tableDnD({
                    onDrop: function(table, row){
                        
                        var list = $.tableDnD.serialize();
                        
                        $.ajax({
                            type: "POST",
                            data: list,
                            url: '<?php echo DOCBASE.PMS_ADMIN_FOLDER; ?>/includes/order_item.php?table=<?php echo MODULE; ?>&offset=<?php echo $offset ?>',
                        });
                    }
                });
                <?php
            } ?>
        });
    </script>
    <?php
} ?>
