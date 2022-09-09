<html>
<?php
define("ADMIN", true);

define("SYSBASE", str_replace("\\", "/", realpath(dirname(__FILE__)."/../../../../")."/"));

require_once(SYSBASE."common/lib.php");
require_once(SYSBASE."common/define.php");

if(!isset($_SESSION['user'])) exit();
elseif($_SESSION['user']['type'] == "registered"){
    unset($_SESSION['user']);
    exit();
}
if(isset($_POST['id']) && isset($_SESSION['user']['id'])){
    $id_booking = (int)$_POST['id'];
    if(is_numeric($id_booking)){ ?>
        <head>
        <script>
            function printElem(elem){
                var popup = window.open('', 'print', 'height=800,width=600');
                popup.document.write('<html><head><title>'+document.title+'</title><link rel="stylesheet" href="<?php echo DOCBASE.PMS_ADMIN_FOLDER.'/css/print.css'; ?>"/></head><body>'+document.getElementById(elem).innerHTML+'</body></html>');
                setTimeout(function(){ 
                    popup.document.close();
                    popup.focus();
                    popup.print();
                    popup.close();    
                }, 600);
                return true;
            }
        </script>
        <style>
             .white-popup-block {
                 width:850px;
                 max-width:none;
            }
        </style>
        </head>
        <body>
        <div class="white-popup-block" id="popup-booking-<?php echo $id_booking; ?>">
            <?php
            $result_booking = $pms_db->query('SELECT * FROM pm_booking WHERE id = '.$id_booking);
            if($result_booking !== false && $pms_db->last_row_count() > 0){
                
                $row = $result_booking->fetch();
            
                echo '
                <h2>'.$pms_texts['BOOKING_SUMMARY'].' #'.$id_booking.'</h2>
                <a href="index.php?view=form&id='.$id_booking.'" class="pull-right action-btn"><i class="fas fa-fw fa-edit"></i></a>
                <a href="#" onclick="javascript:printElem(\'popup-booking-'.$id_booking.'\');return false;" class="pull-right action-btn"><i class="fas fa-fw fa-print"></i></a>
                
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th width="50%">'.$pms_texts['BOOKING_DETAILS'].'</th>
                        <th width="50%">'.$pms_texts['BILLING_ADDRESS'].'</th>
                    </tr>
                    <tr>
                        <td>
                            '.$pms_texts['CHECK_IN'].' <strong>'.gmstrftime(PMS_DATE_FORMAT, $row['from_date']).'</strong><br>
                            '.$pms_texts['CHECK_OUT'].' <strong>'.gmstrftime(PMS_DATE_FORMAT, $row['to_date']).'</strong><br>
                            <strong>'.$row['nights'].'</strong> '.$pms_texts['NIGHTS'].'<br>
                            <strong>'.($row['adults']+$row['children']).'</strong> '.$pms_texts['PERSONS'].' - 
                            '.$pms_texts['ADULTS'].': <strong>'.$row['adults'].'</strong> / 
                            '.$pms_texts['CHILDREN'].': <strong>'.$row['children'].'</strong>';
                            if($row['comments'] != '') echo '<p><b>'.$pms_texts['COMMENTS'].'</b><br>'.nl2br($row['comments']).'</p>';
                            echo '
                        </td>
                        <td>
                            '.$row['firstname'].' '.$row['lastname'].'<br>';
                            if($row['company'] != '') echo $pms_texts['COMPANY'].' : '.$row['company'].'<br>';
                            echo nl2br($row['address']).'<br>
                            '.$row['postcode'].' '.$row['city'].'<br>
                            '.$pms_texts['PHONE'].' : '.$row['phone'].'<br>';
                            if($row['mobile'] != '') echo $pms_texts['MOBILE'].' : '.$row['mobile'].'<br>';
                            echo $pms_texts['EMAIL'].' : '.$row['email'].'
                        </td>
                    </tr>
                </table>';
                
                $result_room = $pms_db->query('SELECT * FROM pm_booking_room WHERE id_booking = '.$row['id']);
                if($result_room !== false && $pms_db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$pms_texts['ROOM'].'</th>
                            <th>'.$pms_texts['PERSONS'].'</th>
                            <th class="text-center">'.$pms_texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_room as $room){
                            echo
                            '<tr>
                                <td>'.$room['title'].'</td>
                                <td>
                                    '.($room['adults']+$room['children']).' '.pms_getAltText($pms_texts['PERSON'], $pms_texts['PERSONS'], ($room['adults']+$room['children'])).' (';
                                    if($room['adults'] > 0) echo $room['adults'].' '.pms_getAltText($pms_texts['ADULT'], $pms_texts['ADULTS'], $room['adults']).' ';
                                    if($room['children'] > 0) echo $room['children'].' '.pms_getAltText($pms_texts['CHILD'], $pms_texts['CHILDREN'], $room['children']).' ';
                                    echo ')
                                </td>
                                <td class="text-right" width="15%">'.pms_formatPrice($room['amount']*PMS_CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                
                $result_service = $pms_db->query('SELECT * FROM pm_booking_service WHERE id_booking = '.$row['id']);
                if($result_service !== false && $pms_db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$pms_texts['SERVICES'].'</th>
                            <th>'.$pms_texts['QUANTITY'].'</th>
                            <th class="text-center">'.$pms_texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_service as $service){
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
                
                $result_activity = $pms_db->query('SELECT * FROM pm_booking_activity WHERE id_booking = '.$row['id']);
                if($result_activity !== false && $pms_db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$pms_texts['ACTIVITY'].'</th>
                            <th>'.$pms_texts['DURATION'].'</th>
                            <th>'.$pms_texts['DATE'].'</th>
                            <th>'.$pms_texts['PERSONS'].'</th>
                            <th class="text-center">'.$pms_texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_activity as $activity){
                            echo
                            '<tr>
                                <td>'.$activity['title'].'</td>
                                <td>'.$activity['duration'].'</td>
                                <td>'.gmstrftime(PMS_DATE_FORMAT.' '.PMS_TIME_FORMAT, $activity['date']).'</td>
                                <td>
                                    '.($activity['adults']+$activity['children']).' '.pms_getAltText($pms_texts['PERSON'], $pms_texts['PERSONS'], ($activity['adults']+$activity['children'])).' (';
                                    if($activity['adults'] > 0) echo $activity['adults'].' '.pms_getAltText($pms_texts['ADULT'], $pms_texts['ADULTS'], $activity['adults']).' ';
                                    if($activity['children'] > 0) echo $activity['children'].' '.pms_getAltText($pms_texts['CHILD'], $pms_texts['CHILDREN'], $activity['children']).' ';
                                    echo ')
                                </td>
                                <td class="text-right" width="15%">'.pms_formatPrice($activity['amount']*PMS_CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                echo '
                <table class="table table-responsive table-bordered">';
                    
                    if(isset($row['discount_amount']) && $row['discount_amount'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$pms_texts['DISCOUNT'].'</th>
                            <td class="text-right">- '.pms_formatPrice($row['discount_amount']*PMS_CURRENCY_RATE).'</td>
                        </tr>';
                    }
                    
                    $result_tax = $pms_db->query('SELECT * FROM pm_booking_tax WHERE id_booking = '.$row['id']);
                    if($result_tax !== false && $pms_db->last_row_count() > 0){
                        foreach($result_tax as $tax){
                            echo '
                            <tr>
                                <th class="text-right">'.$tax['name'].'</th>
                                <td class="text-right">'.pms_formatPrice($tax['amount']*PMS_CURRENCY_RATE).'</td>
                            </tr>';
                        }
                    }
                    
                    echo '
                    <tr>
                        <th class="text-right">'.$pms_texts['TOTAL'].' ('.$pms_texts['INCL_TAX'].')</th>
                        <td class="text-right" width="15%"><b>'.pms_formatPrice($row['total']*PMS_CURRENCY_RATE).'</b></td>
                    </tr>';
                    
                    if(PMS_ENABLE_DOWN_PAYMENT == 1 && $row['down_payment'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$pms_texts['DOWN_PAYMENT'].' ('.$pms_texts['INCL_TAX'].')</th>
                            <td class="text-right" width="15%"><b>'.pms_formatPrice($row['down_payment']*PMS_CURRENCY_RATE).'</b></td>
                        </tr>';
                    }
                    echo '
                </table>';
                    
                echo '<p><strong>'.$pms_texts['PAYMENT'].'</strong><p>';
                
                echo '<p>'.$pms_texts['PAYMENT_METHOD'].' : '.$row['payment_option'].'<br>';
                echo $pms_texts['STATUS'].': ';
                switch($row['status']){
                    case 1: echo $pms_texts['PENDING']; break;
                    case 2: echo $pms_texts['CANCELLED']; break;
                    case 3: echo $pms_texts['REJECTED_PAYMENT']; break;
                    case 4: echo $pms_texts['PAID']; break;
                    default: echo $pms_texts['PENDING']; break;
                }
                echo '<br>';
                
				$result_payment = $pms_db->query('SELECT * FROM pm_booking_payment WHERE id_booking = '.$row['id']);
				if($result_payment !== false && $pms_db->last_row_count() > 0){
					echo '
					<table class="table table-responsive table-bordered">
						<tr>
							<th>'.$pms_texts['DATE'].'</th>
							<th>'.$pms_texts['PAYMENT_METHOD'].'</th>
							<th class="text-center">'.$pms_texts['AMOUNT'].'</th>
						</tr>';
						foreach($result_payment as $payment){
							echo
							'<tr>
								<td>'.gmstrftime(PMS_DATE_FORMAT.' '.PMS_TIME_FORMAT, $payment['date']).'</td>
								<td>'.$payment['method'].'</td>
								<td class="text-right" width="15%">'.pms_formatPrice($payment['amount']*PMS_CURRENCY_RATE).'</td>
							</tr>';
						}
						echo '
					</table>';
				}
					
                if($row['status'] == 4){
                    if(!empty($row['down_payment'])) echo $pms_texts['DOWN_PAYMENT'].' : '.pms_formatPrice($row['down_payment']*PMS_CURRENCY_RATE).'<br>';
                    echo '<b>'.$pms_texts['BALANCE'].' : '.pms_formatPrice(($row['total']-$row['down_payment'])*PMS_CURRENCY_RATE).'</b>';
                }else
                    echo '<b>'.$pms_texts['BALANCE'].' : '.pms_formatPrice($row['total']*PMS_CURRENCY_RATE).'</b><br>';
                echo '</p>';
            } ?>
        </div>
        </body>
        <?php
    }
} ?>
</html>
