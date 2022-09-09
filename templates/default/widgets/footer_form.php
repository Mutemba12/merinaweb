<?php debug_backtrace() || die ("Direct access not permitted"); ?>
<div class="searchWrapper pull-left">
    <?php $csrf_token = pms_get_token("search"); ?>

    <form method="post" action="<?php echo DOCBASE.$pms_sys_pages['search']['alias']; ?>" role="form" class="form-inline">
        <input type="text" class="form-control" name="global-search" placeholder="<?php echo $pms_texts['SEARCH']; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <button type="submit" class="btn btn-primary" name="send"><i class="fa fa-search"></i></button>
    </form>
</div>
<?php
if(PMS_LANG_ENABLED){
    if(count($pms_langs) > 0){ ?>
        <div class="dropup">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                <img src="<?php echo $pms_langs[PMS_LANG_TAG]['file']; ?>" alt="<?php echo $pms_langs[PMS_LANG_TAG]['title']; ?>"> <?php echo $pms_langs[PMS_LANG_TAG]['title']; ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="lang-btn" id="lang-menu">
                <?php
                foreach($pms_langs as $row){
                    $title_lang = $row['title']; ?>
                    <li><a href="<?php echo DOCBASE.$row['tag']; ?>"><img src="<?php echo $row['file']; ?>" alt="<?php echo $title_lang; ?>"> <?php echo $title_lang; ?></a></li>
                    <?php
                } ?>
            </ul>
        </div>
        <?php
    }
}
if(PMS_CURRENCY_ENABLED){
    if(count($pms_currencies) > 0){ ?>
        <div class="dropup">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                <?php echo PMS_CURRENCY_CODE." ".PMS_CURRENCY_SIGN; ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="lang-btn" id="lang-menu">
                <?php
                foreach($pms_currencies as $row){ ?>
                    <li><a href="<?php echo pms_getUrl(); ?>" data-action="<?php echo pms_getFromTemplate("common/change_currency.php"); ?>?curr=<?php echo $row['id']; ?>" class="ajax-link"><?php echo $row['code']." ".$row['sign']; ?></a></li>
                    <?php
                } ?>
            </ul>
        </div>
        <?php
    }
} ?>
