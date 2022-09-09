<?php require(pms_getFromTemplate("common/header.php", false)); ?>

<section id="page">
    
    <?php include(pms_getFromTemplate("common/page_header.php", false)); ?>
    
    <section id="content" class="pt30 pb30">
        <div class="container">
            <?php echo $page['text']; ?>
        </div>
    </section>
</section>
