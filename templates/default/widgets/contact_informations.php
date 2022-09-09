<?php debug_backtrace() || die ('Direct access not permitted'); ?>
<div itemscope itemtype="http://schema.org/Corporation">
    <h3 itemprop="name"><?php echo PMS_OWNER; ?></h3>
    <address>
        <p>
            <?php if(PMS_ADDRESS != '') : ?><span class="fas fa-fw fa-map-marker"></span> <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo nl2br(PMS_ADDRESS); ?></span><br><?php endif; ?>
            <?php if(PMS_PHONE != '') : ?><span class="fas fa-fw fa-phone"></span> <a href="tel:<?php echo PMS_PHONE; ?>" itemprop="telephone" dir="ltr"><?php echo PMS_PHONE; ?></a><br><?php endif; ?>
            <?php if(PMS_MOBILE != '') : ?><span class="fas fa-fw fa-mobile"></span> <a href="tel:<?php echo PMS_MOBILE; ?>" itemprop="telephone" dir="ltr"><?php echo PMS_MOBILE; ?></a><br><?php endif; ?>
            <?php if(PMS_FAX != '') : ?><span class="fas fa-fw fa-fax"></span> <span itemprop="faxNumber" dir="ltr"><?php echo PMS_FAX; ?></span><br><?php endif; ?>
            <?php if(PMS_EMAIL != '') : ?><span class="fas fa-fw fa-envelope"></span> <a itemprop="email" dir="ltr" href="mailto:<?php echo PMS_EMAIL; ?>"><?php echo PMS_EMAIL; ?></a><?php endif; ?>
        </p>
    </address>
</div>
<p class="lead">
    <?php
    $result_social = $pms_db->query('SELECT * FROM pm_social WHERE checked = 1 ORDER BY `rank`');
    if($result_social !== false){
        foreach($result_social as $row){ ?>
            <a href="<?php echo $row['url']; ?>" target="_blank">
                <i class="fab fa-fw fa-<?php echo $row['type']; ?>"></i>
            </a>
            <?php
        }
    } ?>
</p>
