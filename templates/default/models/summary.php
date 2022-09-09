<?php
if(!isset($_SESSION['book']) || count($_SESSION['book']) == 0){
    header('Location: '.DOCBASE.$pms_sys_pages['booking']['alias']);
    exit();
}else
    $_SESSION['book']['step'] = 'summary';

require(pms_getFromTemplate('common/header.php', false)); ?>

<section id="page">
    
    <?php include(pms_getFromTemplate('common/page_header.php', false)); ?>

    <div id="content" class="pt30 pb20">
        <div class="container">
            
            <div class="row mb30" id="booking-breadcrumb">
                <div class="col-sm-2 col-sm-offset-<?php echo isset($_SESSION['book']['activities']) ? '1' : '2'; ?>">
                    <a href="<?php echo DOCBASE.$pms_sys_pages['booking']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-calendar"></i>
                            <span><?php echo $pms_sys_pages['booking']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <?php
                if(isset($_SESSION['book']['activities'])){ ?>
                    <div class="col-sm-2">
                        <a href="<?php echo DOCBASE.$pms_sys_pages['booking-activities']['alias']; ?>">
                            <div class="breadcrumb-item done">
                                <i class="fas fa-fw fa-ticket-alt"></i>
                                <span><?php echo $pms_sys_pages['booking-activities']['name']; ?></span>
                            </div>
                        </a>
                    </div>
                    <?php
                } ?>
                <div class="col-sm-2">
                    <a href="<?php echo DOCBASE.$pms_sys_pages['details']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-info-circle"></i>
                            <span><?php echo $pms_sys_pages['details']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-sm-2">
                    <div class="breadcrumb-item active">
                        <i class="fas fa-fw fa-list"></i>
                        <span><?php echo $pms_sys_pages['summary']['name']; ?></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="breadcrumb-item">
                        <i class="fas fa-fw fa-credit-card"></i>
                        <span><?php echo $pms_sys_pages['payment']['name']; ?></span>
                    </div>
                </div>
            </div>
            
            <?php
            if($page['text'] != ""){ ?>
                <div class="clearfix mb20"><?php echo $page['text']; ?></div>
                <?php
            } ?>
            
            <form method="post" action="<?php echo DOCBASE.$pms_sys_pages['payment']['alias']; ?>">
            
                <?php
                echo '
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th width="50%">'.$pms_texts['BOOKING_DETAILS'].'</th>
                        <th width="50%">'.$pms_texts['BILLING_ADDRESS'].'</th>
                    </tr>
                    <tr>
                        <td>';
							if(isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0){
								echo $pms_texts['CHECK_IN'].' <strong>'.gmstrftime(PMS_DATE_FORMAT, $_SESSION['book']['from_date']).'</strong><br>
								'.$pms_texts['CHECK_OUT'].' <strong>'.gmstrftime(PMS_DATE_FORMAT, $_SESSION['book']['to_date']).'</strong><br>
								<strong>'.$_SESSION['book']['nights'].'</strong> '.pms_getAltText($pms_texts['NIGHT'], $pms_texts['NIGHTS'], $_SESSION['book']['nights']).'<br>
								<strong>'.($_SESSION['book']['adults']+$_SESSION['book']['children']).'</strong> '.pms_getAltText($pms_texts['PERSON'], $pms_texts['PERSONS'], ($_SESSION['book']['adults']+$_SESSION['book']['children'])).' - 
								'.pms_getAltText($pms_texts['ADULT'], $pms_texts['ADULTS'], $_SESSION['book']['adults']).': <strong>'.$_SESSION['book']['adults'].'</strong> / 
								'.pms_getAltText($pms_texts['CHILD'], $pms_texts['CHILDREN'], $_SESSION['book']['children']).': <strong>'.$_SESSION['book']['children'].'</strong>';
								if($_SESSION['book']['comments'] != '') echo '<p><b>'.$pms_texts['COMMENTS'].'</b><br>'.nl2br($_SESSION['book']['comments']).'</p>';
							}
							echo '
                        </td>
                        <td>
                            '.$_SESSION['book']['firstname'].' '.$_SESSION['book']['lastname'].'<br>';
                            if($_SESSION['book']['company'] != '') echo $pms_texts['COMPANY'].' : '.$_SESSION['book']['company'].'<br>';
                            echo nl2br($_SESSION['book']['address']).'<br>
                            '.$_SESSION['book']['postcode'].' '.$_SESSION['book']['city'].'<br>
                            '.$pms_texts['PHONE'].' : '.$_SESSION['book']['phone'].'<br>';
                            if($_SESSION['book']['mobile'] != '') echo $pms_texts['MOBILE'].' : '.$_SESSION['book']['mobile'].'<br>';
                            echo $pms_texts['EMAIL'].' : '.$_SESSION['book']['email'].'
                        </td>
                    </tr>
                </table>';
                        
                if(isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$pms_texts['ROOM'].'</th>
                            <th>'.$pms_texts['PERSONS'].'</th>
                            <th class="text-center">'.$pms_texts['TOTAL'].'</th>
                        </tr>';
                        foreach($_SESSION['book']['rooms'] as $id_room => $rooms){
                            foreach($rooms as $index => $room){
                                echo
                                '<tr>
                                    <td>'.$room['title'].'</td>
                                    <td>
                                        '.($room['adults']+$room['children']).' '.pms_getAltText($pms_texts['PERSON'], $pms_texts['PERSONS'], ($room['adults']+$room['children'])).': ';
                                        if($room['adults'] > 0) echo $room['adults'].' '.pms_getAltText($pms_texts['ADULT'], $pms_texts['ADULTS'], $room['adults']).' ';
                                        if($room['children'] > 0){
                                            echo $room['children'].' '.pms_getAltText($pms_texts['CHILD'], $pms_texts['CHILDREN'], $room['children']).' ';
                                            if(isset($room['child_age'])){
                                                echo '('.implode(' '.$pms_texts['YO'].', ', $room['child_age']).' '.$pms_texts['YO'].')';
                                            }
                                        }
                                        echo '
                                    </td>
                                    <td class="text-right" width="15%">'.pms_formatPrice($room['amount']*PMS_CURRENCY_RATE).'</td>
                                </tr>';
                            }
                        }
                        echo '
                    </table>';
                }
                
                if(isset($_SESSION['book']['extra_services']) && count($_SESSION['book']['extra_services']) > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$pms_texts['SERVICE'].'</th>
                            <th>'.$pms_texts['QUANTITY'].'</th>
                            <th class="text-center">'.$pms_texts['TOTAL'].'</th>
                        </tr>';
                        foreach($_SESSION['book']['extra_services'] as $id_service => $service){
                            echo
                            '<tr>
                                <td>'.$service['title'].'</td>
                                <td>'.$service['qty'].'</td>
                                <td class="text-right" width="15%">'.pms_formatPrice($service['amount']*PMS_CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                
                if(isset($_SESSION['book']['activities']) && count($_SESSION['book']['activities']) > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$pms_texts['ACTIVITY'].'</th>
                            <th>'.$pms_texts['DURATION'].'</th>
                            <th>'.$pms_texts['DATE'].'</th>
                            <th>'.$pms_texts['PERSONS'].'</th>
                            <th class="text-center">'.$pms_texts['TOTAL'].'</th>
                        </tr>';
                        foreach($_SESSION['book']['activities'] as $id_activity => $activity){
                            echo
                            '<tr>
                                <td>'.$activity['title'].'</td>
                                <td>'.$activity['duration'].'</td>
                                <td>'.gmstrftime(PMS_DATE_FORMAT.' '.PMS_TIME_FORMAT, $activity['session_date']).'</td>
                                <td>
                                    '.($activity['adults']+$activity['children']).' '.pms_getAltText($pms_texts['PERSON'], $pms_texts['PERSONS'], ($activity['adults']+$activity['children'])).': ';
                                    if($activity['adults'] > 0) echo $activity['adults'].' '.pms_getAltText($pms_texts['ADULT'], $pms_texts['ADULTS'], $activity['adults']).' ';
                                    if($activity['children'] > 0) echo $activity['children'].' '.pms_getAltText($pms_texts['CHILD'], $pms_texts['CHILDREN'], $activity['children']).' ';
                                    echo '
                                </td>
                                <td class="text-right" width="15%">'.pms_formatPrice($activity['amount']*PMS_CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                echo '
                <table class="table table-responsive table-bordered">';
                    
                    if(isset($_SESSION['book']['discount_amount']) && $_SESSION['book']['discount_amount'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$pms_texts['DISCOUNT'].'</th>
                            <td class="text-right">- '.pms_formatPrice($_SESSION['book']['discount_amount']*PMS_CURRENCY_RATE).'</td>
                        </tr>';
                    }
                    
                    if(isset($_SESSION['book']['taxes'])){
						$tax_id = 0;
						$result_tax = $pms_db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = '.PMS_LANG_ID.' ORDER BY `rank`');
						$result_tax->bindParam(':tax_id', $tax_id);
						foreach($_SESSION['book']['taxes'] as $tax_id => $taxes){
							$tax_amount = 0;
							foreach($taxes as $amount) $tax_amount += $amount;
							if($tax_amount > 0){
								if($result_tax->execute() !== false && $pms_db->last_row_count() > 0){
									$tax = $result_tax->fetch();
									
									echo '
									<tr>
										<th class="text-right">'.$tax['name'].'</th>
										<td class="text-right">'.pms_formatPrice($tax_amount*PMS_CURRENCY_RATE).'</td>
									</tr>';
								}
							}
						}
					}
                    
                    echo '
                    <tr>
                        <th class="text-right">'.$pms_texts['TOTAL'].' ('.$pms_texts['INCL_TAX'].')</th>
                        <td class="text-right" width="15%"><b>'.pms_formatPrice($_SESSION['book']['total']*PMS_CURRENCY_RATE).'</b></td>
                    </tr>';
                    
                    if(PMS_ENABLE_DOWN_PAYMENT == 1 && $_SESSION['book']['down_payment'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$pms_texts['DOWN_PAYMENT'].' ('.$pms_texts['INCL_TAX'].')</th>
                            <td class="text-right" width="15%"><b>'.pms_formatPrice($_SESSION['book']['down_payment']*PMS_CURRENCY_RATE).'</b></td>
                        </tr>';
                    }
                    echo '
                </table>'; ?>
                
                <a class="btn btn-default btn-lg pull-left" href="<?php echo DOCBASE.$pms_sys_pages['details']['alias']; ?>"><i class="fas fa-fw fa-angle-left"></i> <?php echo $pms_texts['PREVIOUS_STEP']; ?></a>
                <button type="submit" name="confirm_booking" class="btn btn-primary btn-lg pull-right"><?php echo $pms_texts['CONFIRM_BOOKING']; ?> <i class="fas fa-fw fa-angle-right"></i></button>
            </form>
        </div>
    </div>
</section>
