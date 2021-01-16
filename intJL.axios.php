<?php

include_once 'class/dbh.inc.php';
include_once 'class/phhdate.inc.php';
include_once 'class/variables.inc.php';
include_once 'class/joblistwork.inc.php';

$received_data = json_decode(file_get_contents("php://input"));

$data_output = array();
$action = $received_data->action;

function parseJobcode($jobcode) {
    $jclength = strlen($jobcode);
    #echo "jc_length = $jc_length.\n";
    #echo "strpos [ = " . strpos($jobcode, '[') . '\n';
    if (strpos($jobcode, '[') === 0) {
        //this is new jobcode
        #echo "new jobcode\n";
        $endpos = strpos($jobcode, ']');
        $cleanJobCode = trim(substr($jobcode, 1, $endpos - 1));
    } else {
        #echo "old jobcode\n";
        //this is old jobcode
        if ($jclength <= 28) {
            # echo "ok\n";
            $cleanJobCode = trim($jobcode);
        } else {
            # echo "fail\n";
            return 'fail';
        }
    }
    #echo "cleanjobcode = $cleanJobCode<br>";
    if (strlen($cleanJobCode) == 28 || strlen($cleanJobCode) == 24) {
        $len = strlen($cleanJobCode) - 5;
        $cleanJobCode = substr($cleanJobCode, 0, $len);
        #echo "cleanjobcode = $cleanJobCode<br>" . strlen($cleanJobCode);
    }
    if (strlen($cleanJobCode) == 19 || strlen($cleanJobCode) == 23) {
        return $cleanJobCode;
    } else {
        return 'fail';
    }
}

switch ($action) {
    case 'parseJobCode':
        $jobcode = $received_data->jobcode;
        $parseJobCode = parseJobcode($jobcode);
        if ($parseJobCode != 'fail') {
            $resp = array('status' => 'ok', 'msg' => $parseJobCode);
        } else {
            $resp = array('status' => 'error', 'msg' => 'Cannot parse Jobcode, Please Check the format');
        }
        echo json_encode($resp);
        break;
    case 'getJoblistDetail':
        $jobcode = parseJobcode($received_data->jobcode);
        try {
            if ($jobcode == 'fail') {
                throw new Exception('Cannot parse Jobcode, Please Check the Format');
            }
            $objJW2 = new JOB_WORK_2($jobcode);
            if ($objJW2->get_sid() == null) {
                throw new Exception('Cannot find records for ' . $jobcode . '.');
            } else {
                $sid = $objJW2->get_sid();
                $period = $objJW2->get_period();
                $sch_details = get_SchedulingDetailsBySidFromLocal($period, $sid);
                $out_arr = array('status' => 'ok', 'schPeriod' => $period, 'schDetail' => $sch_details);
            }
        } catch (Exception $ex) {
            $out_arr = array('status' => 'error', 'msg' => $ex->getMessage());
        }
        echo json_encode($out_arr);
        break;
    case 'generateIntermediateJL':
        $qid = $received_data->qid;
        $quono = $received_data->quono;
        $jobcode = $received_data->jobcode;
        #$origin_period = $received_data->origin_period;
        $intData = json_decode(json_encode($received_data->intData), true);
        #var_dump($received_data);
        #print_r($received_data);
        try {
            $objJW2 = new JOB_WORK_2($jobcode);
            $sid = $objJW2->get_sid();
            $period = $objJW2->get_period();
            //fetch original datas
            $sch_details = get_SchedulingDetailsBySidFromLocal($period, $sid);
            $quo_details = get_QuotationDetailsByQidFromLocal($quono, $qid);
            $ord_details = get_OrderlistDetailsByJoblistFromLocal($period, $jobcode);
            //get PHH Production department admin details
            $phhprod_details = get_PHHPRODADMIN_Detail();
            $objPeriod = new Period();
            //set current period
            $currPeriod = $objPeriod->getcurrentPeriod();
            //generate intermediate quono
            $int_quono = get_int_quono($currPeriod);
            #echo "int_quono = $int_quono\n";
            //generate orderlist runningno
            $ordrunningno = get_int_runningno($currPeriod);


            $intermediate_array = generate_intermediateArray($quo_details, $phhprod_details, $intData, $int_quono);
            $insIntResult = issue_intermediateQuotation($intermediate_array, $currPeriod);
            if ($insIntResult != 'fail') {
                $intermediate_array['qid'] = $insIntResult;
            } else {
                throw new Exception('Failed to generate new Quotation');
            }
            $intermediate_remark_array = generate_intermediateRemarks($jobcode, $quono, $int_quono, $intData, $phhprod_details, $quo_details);
            $insIntRemResult = issue_intermediateRemarks($intermediate_remark_array, $currPeriod);
            if ($insIntRemResult == 'fail') {
                throw new Exception('Failed to generate remarks');
            }
            $orderlist_array = generate_orderlistArray($intermediate_array, $ordrunningno, $ord_details, $intermediate_remark_array);
            $insOrdResult = issue_orderlist($orderlist_array, $currPeriod);

            if ($insOrdResult == 'fail') {
                throw new Exception('Failed to generate Orderlist');
            }
            $scheduling_array = generate_schedulingArray($orderlist_array, $sch_details);
            $insSchResult = issue_scheduling($scheduling_array, $currPeriod);

            if ($insSchResult == 'fail') {
                throw new Exception('Failed to generate Scheduling');
            }

            $todayorderno = get_todayorderno($currPeriod);
            $runningno_array = generate_runningnoArray($orderlist_array, $ordrunningno, $todayorderno);
            $insRunnoResult = issue_runningno($runningno_array, $currPeriod);

            if ($insRunnoResult == 'fail') {
                throw new Exception('Failed to generate Runningno');
            }
            #echo "intermediate_array =\n";
            #print_r($intermediate_array);
            #echo "Insert result = $insIntResult\n";
            #echo "intermediate_remark_array = \n";
            #print_r($intermediate_remark_array);
            #echo "Insert Remark result = $insIntRemResult\n";
            #echo "orderlist_array = \n";
            #print_r($orderlist_array);
            #echo "Insert Orderlist result = $insOrdResult\n";
            #echo "schedyling_array = \n";
            #print_r($scheduling_array);
            #echo "Insert Scheduling result = $insSchResult\n";
            #echo "runningno_array = \n";
            #print_r($runningno_array);
            #echo "Insert Runningno result = $insRunnoResult\n";
            $arr_out = array('status' => 'ok', 'new_quono' => $int_quono, 'issue_period' => $currPeriod, 'ord_data' => $orderlist_array);
        } catch (Exception $ex) {
            $arr_out = array('status' => 'error', 'msg' => $ex->getMessage());
        }
        echo json_encode($arr_out);
        break;
}

function get_int_quono($period) {
    $com = "pst";
    $quotab = "quotation_" . $com . "_" . $period;
    $qr = "SELECT DISTINCT quono FROM $quotab WHERE quono LIKE 'PRD%' ORDER BY quono DESC";
    $objSQL = new SQL($qr);
    #echo "sql = $qr\n";
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        $quorunningno = (int) substr($result['quono'], -3) + 1;
    } else {
        $quorunningno = 1;
    }
    $int_quono = "PRD " . $period . " " . sprintf("%03d", $quorunningno);
    return $int_quono;
}

function get_int_runningno($period) {
    $runtab = "runningno_" . $period;
    $qr = "SELECT * FROM $runtab WHERE quono LIKE 'PRD%' ORDER BY rnid DESC";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        $ordrunningno = (int) $result['runno'] + 1;
    } else {
        $ordrunningno = 9000;
    }
    return $ordrunningno;
}

function get_SchedulingDetailsBySidFromLocal($period, $sid) {
    $tblname = 'production_scheduling_' . $period;
    $qr = "SELECT * FROM $tblname WHERE sid = $sid AND status = 'active'";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function get_OrderlistDetailsByJoblistFromLocal($period, $jobcode) {
    $com = "pst";
    $jlfor = substr($jobcode, 0, 2);
    $co_code = substr($jobcode, 3, 3);
    $yearmonth = '20' . substr($jobcode, 7, 2) . '-' . substr($jobcode, 9, 2);
    $runningno = (int) substr($jobcode, 12, 4);
    $jobno = (int) substr($jobcode, 17, 2);
    $tblname = 'orderlist_' . $com . '_' . $period;
    $qr = "SELECT * FROM $tblname WHERE quono LIKE '$co_code%' AND runningno = $runningno AND jobno = $jobno";
    #echo "qr = $qr\n";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function get_QuotationDetailsByQidFromLocal($quono, $qid) {
    $com = "pst";
    $period = substr($quono, 4, 4);
    $tblname = 'quotation_' . $com . "_" . $period;
    $qr = "SELECT * FROM $tblname WHERE qid = $qid AND quono = '$quono'";
    #echo "qr = $qr\n";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function get_PHHPRODADMIN_Detail() {
    $com = "pst";
    $qr = "SELECT * FROM customer_$com WHERE co_name LIKE '%PHH Production%'";
    #echo "sql = $qr\n";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result;
    } else {
        return 'empty';
    }
}

function issue_intermediateQuotation($intermediate_array, $issue_period) {
    $com = "pst";
    $quotab = "quotation_" . $com . "_" . $issue_period;
    $cnt = 0;
    $arrCnt = count($intermediate_array);
    $qr = "INSERT INTO $quotab SET ";
    foreach ($intermediate_array as $key => $val) {
        $cnt++;
        $qr .= " $key =:$key ";
        if ($cnt != $arrCnt) {
            $qr .= " , ";
        }
    }
    $objSQL = new SQLBINDPARAM($qr, $intermediate_array);
    $result = $objSQL->InsertData2();
    if ($result == 'insert ok!') {
        $qr2 = "SELECT * FROM $quotab WHERE quono like 'PRD%' ORDER BY qid DESC";
        $objSQL = new SQL($qr2);
        $result = $objSQL->getResultOneRowArray();
        return $result['qid'];
    } else {
        return 'fail';
    }
}

function issue_intermediateRemarks($intermediate_remark_array, $issue_period) {
    $com = "pst";
    $quotab = "quotation_remarks_" . $com . "_" . $issue_period;
    $cnt = 0;
    $arrCnt = count($intermediate_remark_array);
    $qr = "INSERT INTO $quotab SET ";
    foreach ($intermediate_remark_array as $key => $val) {
        $cnt++;
        $qr .= " $key =:$key ";
        if ($cnt != $arrCnt) {
            $qr .= " , ";
        }
    }
    $objSQL = new SQLBINDPARAM($qr, $intermediate_remark_array);
    $result = $objSQL->InsertData2();
    if ($result == 'insert ok!') {
        return 'ok';
    } else {
        return 'fail';
    }
}

function issue_orderlist($orderlist_array, $issue_period) {
    $com = "pst";
    $ordtab = "orderlist_" . $com . "_" . $issue_period;
    $cnt = 0;
    $arrCnt = count($orderlist_array);
    $qr = "INSERT INTO $ordtab SET ";
    foreach ($orderlist_array as $key => $val) {
        $cnt++;
        $qr .= " $key =:$key ";
        if ($cnt != $arrCnt) {
            $qr .= " , ";
        }
    }
    $objSQL = new SQLBINDPARAM($qr, $orderlist_array);
    $result = $objSQL->InsertData2();
    if ($result == 'insert ok!') {
        return 'ok';
    } else {
        return 'fail';
    }
}

function issue_scheduling($scheduling_array, $issue_period) {
    $schtab = "production_scheduling" . "_" . $issue_period;
    $cnt = 0;
    $arrCnt = count($scheduling_array);
    $qr = "INSERT INTO $schtab SET ";
    foreach ($scheduling_array as $key => $val) {
        $cnt++;
        $qr .= " $key =:$key ";
        if ($cnt != $arrCnt) {
            $qr .= " , ";
        }
    }
    $objSQL = new SQLBINDPARAM($qr, $scheduling_array);
    $result = $objSQL->InsertData2();
    if ($result == 'insert ok!') {
        return 'ok';
    } else {
        return 'fail';
    }
}

function issue_runningno($runningno_array, $issue_period) {
    $runtab = "runningno" . "_" . $issue_period;
    $cnt = 0;
    $arrCnt = count($runningno_array);
    $qr = "INSERT INTO $runtab SET ";
    foreach ($runningno_array as $key => $val) {
        $cnt++;
        $qr .= " $key =:$key ";
        if ($cnt != $arrCnt) {
            $qr .= " , ";
        }
    }
    $objSQL = new SQLBINDPARAM($qr, $runningno_array);
    $result = $objSQL->InsertData2();
    if ($result == 'insert ok!') {
        return 'ok';
    } else {
        return 'fail';
    }
}

function generate_intermediateArray($ori_quo_dataset, $adm_dataset, $int_data, $int_quono) {
    $dat = $ori_quo_dataset;
    $quoArray = array(
        'bid' => $dat['bid'],
        'currency' => $dat['currency'],
        'quono' => $int_quono,
        'company' => $dat['company'],
        'pagetype' => $dat['pagetype'],
        'custype' => $dat['custype'],
        'cusstatus' => $adm_dataset['status'],
        'cid' => $adm_dataset['cid'],
        'accno' => $adm_dataset['accno'],
        'date' => date("Y-m-d"),
        'terms' => $adm_dataset['terms'],
        'item' => 1,
        'quantity' => $int_data['quantity'],
        'grade' => $dat['grade'],
        'mdt' => $int_data['mdt'],
        'mdw' => $int_data['mdw'],
        'mdl' => $int_data['mdl'],
        'fdt' => $int_data['mdt'],
        'fdw' => $int_data['fdw'],
        'fdl' => $int_data['fdl'],
        'process' => 1, //set as Basic Process
        'mat' => 0,
        'pmach' => 0,
        'cncmach' => 0,
        'other' => 0,
        'unitprice' => 0,
        'amount' => 0,
        'discount' => 0,
        'vat' => 0,
        'gst' => 0,
        'ftz' => null,
        'amountmat' => 0,
        'discountmat' => 0,
        'gstmat' => 0,
        'totalamountmat' => 0,
        'amountpmach' => 0,
        'discountpmach' => 0,
        'gstpmach' => 0,
        'totalamountpmach' => 0,
        'amountcncmach' => 0,
        'discountcncmach' => 0,
        'gstcncmach' => 0,
        'totalamountcncmach' => 0,
        'amountother' => 0,
        'discountother' => 0,
        'gstother' => 0,
        'totalamountother' => 0,
        'totalamount' => 0,
        'mat_disc' => 0,
        'pmach_disc' => 0,
        'aid_quo' => 80, //set as jamal
        'aid_cus' => $adm_dataset['aid_cus'],
        'datetimeissue' => date("Y-m-d H:i:s"),
        'odissue' => 'yes', //immediately will be made into orderlist;
    );
    return $quoArray;
}

function generate_intermediateRemarks($jobcode, $quono, $int_quono, $int_data, $adm_dataset, $ori_dataset) {
    $remarks1 = "QUOTATION.NO : $quono, JOBLIST.NO : $jobcode.";
    $remarks2 = "This order is the intermidiate work pcs that have to cut to prepare for the above joblist no.";
    $remarks3 = "For Internal use only, Production KPI Calculation purposes.";
    $remarks4 = "Zero amount will be charged for this quotation";
    $quoRemarksArray = array(
        'bid' => $ori_dataset['bid'],
        'quono' => $int_quono,
        'cid' => $adm_dataset['cid'],
        'remarks1' => $remarks1,
        'remarks2' => $remarks2,
        'remarks3' => $remarks3,
        'remarks4' => $remarks4,
    );
    return $quoRemarksArray;
}

function generate_orderlistArray($int_array, $ordrunningno, $oriord_dataset, $intrem_dataset) {
    $issue_date = date_format(date_create(), "Y-m-d");
    $issue_time = date_format(date_create(), "H:i:s");
    $issue_datetime = $issue_date . " " . $issue_time;
    if ($issue_time > "08:00:00" && $issue_time < "17:00:59") {
        $objCompleteDT = new DateTime($issue_datetime);
        date_add($objCompleteDT, date_interval_create_from_date_string('2 days'));
        $completion_date = $objCompleteDT->format("d-m-y");
    } else {
        $objIssueDT = new DateTime($issue_datetime);
        date_add($objIssueDT, date_interval_create_from_date_string('1 days'));
        $issue_date = $objIssueDT->format('Y-m-d');
        $objCompleteDT = new DateTime($issue_date);
        date_add($objCompleteDT, date_interval_create_from_date_string('2 days'));
        $completion_date = $objCompleteDT->format("d-m-y");
    }

    $dat = $int_array;
    $ordArray = array(
        'bid' => $dat['bid'],
        'currency' => $dat['currency'],
        'qid' => $dat['qid'],
        'quono' => $dat['quono'],
        'company' => $dat['company'],
        'cusstatus' => $dat['cusstatus'],
        'cid' => $dat['cid'],
        'accno' => $dat['accno'],
        'date' => $dat['date'],
        'terms' => $dat['terms'],
        'noposition' => 1, //only one item
        'item' => $dat['item'],
        'quantity' => $dat['quantity'],
        'grade' => $dat['grade'],
        'mdt' => $dat['mdt'],
        'mdw' => $dat['mdw'],
        'mdl' => $dat['mdl'],
        'fdt' => $dat['fdt'],
        'fdw' => $dat['fdw'],
        'fdl' => $dat['fdl'],
        'process' => $dat['process'],
        'mat' => $dat['mat'],
        'pmach' => $dat['pmach'],
        'cncmach' => $dat['cncmach'],
        'other' => $dat['other'],
        'unitprice' => $dat['unitprice'],
        'amount' => $dat['amount'],
        'discount' => $dat['discount'],
        'vat' => $dat['vat'],
        'gst' => $dat['gst'],
        'ftz' => $dat['ftz'],
        'amountmat' => $dat['amountmat'],
        'discountmat' => $dat['discountmat'],
        'gstmat' => $dat['gstmat'],
        'totalamountmat' => $dat['totalamountmat'],
        'amountpmach' => $dat['amountpmach'],
        'discountpmach' => $dat['discountpmach'],
        'gstpmach' => $dat['gstpmach'],
        'totalamountpmach' => $dat['totalamountpmach'],
        'amountcncmach' => $dat['amountcncmach'],
        'discountcncmach' => $dat['discountcncmach'],
        'gstcncmach' => $dat['gstcncmach'],
        'totalamountcncmach' => $dat['totalamountcncmach'],
        'amountother' => $dat['amountother'],
        'discountother' => $dat['discountother'],
        'gstother' => $dat['gstother'],
        'totalamountother' => $dat['totalamountother'],
        'totalamount' => $dat['totalamount'],
        'aid_quo' => $dat['aid_quo'],
        'aid_cus' => $dat['aid_cus'],
        'datetimeissue_quo' => $dat['datetimeissue'],
        'olremarks' => null,
        'date_issue' => $issue_date,
        'completion_date' => $completion_date,
        'source' => 'PST', //$oriord_dataset['source'],
        'cuttingtype' => $oriord_dataset['cuttingtype'],
        'tol_thkp' => 0.0,
        'tol_thkm' => 0.0,
        'tol_wdtp' => 0.0,
        'tol_wdtm' => 0.0,
        'tol_lghp' => 0.0,
        'tol_lghm' => 0.0,
        'chamfer' => 0.0,
        'flatness' => 'no',
        'ihremarks' => $oriord_dataset['ihremarks'],
        'ivremarks' => $intrem_dataset['remarks1'],
        'ivpono' => null,
        'custoolcode' => null,
        'runningno' => $ordrunningno,
        'jobno' => 1,
        'ivdate' => $completion_date,
        'aid_ol' => 80, //jamal
        'datetimeissue_ol' => $issue_datetime,
        'jlissue' => 'yes',
        'jlreprint' => 'no',
        'jlreprintcount' => 0,
        'docount' => null,
        'dodate' => null,
        'doissue' => 'no',
        'doreprint' => 'no',
        'doreprintcount' => 0,
        'driver' => null,
        'policyno' => 'PST',
        'aid_do' => null,
        'stampsignature' => 'no',
        'aid_stampsignature' => null,
        'datetime_stampsignature' => null,
        'ivissue' => 'no',
        'ivreprint' => 'no',
        'ivreprintcount' => 0,
        'operation' => $oriord_dataset['operation']
    );
    return $ordArray;
}

function generate_schedulingArray($ord_dataset, $orisch_dataset) {
    $schdArray = array(
#'sid' => $notfound, //Auto Increment, this is no need
        'omid' => null, //testing value
        'bid' => $ord_dataset['bid'],
        'qid' => $ord_dataset['qid'],
        'quono' => $ord_dataset['quono'],
        'company' => $ord_dataset['company'],
        'cid' => $ord_dataset['cid'],
        'aid_cus' => $ord_dataset['aid_cus'],
        'quantity' => $ord_dataset['quantity'],
        'grade' => $ord_dataset['grade'],
        'mdt' => $ord_dataset['mdt'],
        'mdw' => $ord_dataset['mdw'],
        'mdl' => $ord_dataset['mdl'],
        'fdt' => $ord_dataset['fdt'],
        'fdw' => $ord_dataset['fdw'],
        'fdl' => $ord_dataset['fdl'],
        'process' => $ord_dataset['process'],
        'source' => $ord_dataset['source'],
        'cuttingtype' => $ord_dataset['cuttingtype'],
        'custoolcode' => $ord_dataset['custoolcode'],
        'cncmach' => $ord_dataset['cncmach'],
        'noposition' => $ord_dataset['noposition'],
        'runningno' => $ord_dataset['runningno'],
        'jobno' => $ord_dataset['jobno'],
        'date_issue' => $ord_dataset['date_issue'],
        'completion_date' => $ord_dataset['completion_date'],
        'ivdate' => $ord_dataset['ivdate'],
        'cst' => null, //Data need to check where it's from
        'csw' => null, //Data need to check where it's from
        'csl' => null, //Data need to check where it's from
        'dateofcompletion' => null, //Data need to check where it's from
        'additional' => null, //Data need to check where it's from
        'jlfor' => $orisch_dataset['jlfor'], //Data need to check where it's from
        'status' => $orisch_dataset['status'], //Data need to check where it's from
        'ownremarks' => 'no', //Data need to check where it's from
        'prodremarks' => '', //Data need to check where it's from
        'stock_size' => null, //Data need to check where it's from
        'stock_month' => null, //Data need to check where it's from
        'operation' => $ord_dataset['operation'],
    );
    return $schdArray;
}

function generate_runningnoArray($ord_dataset, $runningno, $todayorderno) {
    $rnnoArray = array(
        'bid' => $ord_dataset['bid'],
        'date_issue' => $ord_dataset['date_issue'],
        'runno' => $runningno,
        'todayorderno' => $todayorderno,
        'qid' => $ord_dataset['qid'],
        'quono' => $ord_dataset['quono'],
        'cid' => $ord_dataset['cid'],
        'company' => 'PST'
    );
    return $rnnoArray;
}

function get_todayorderno($period) {
    $runtab = "runningno_" . $period;
    $todate = date_format(date_create(), "Y-m-d");
    $qr = "SELECT * FROM $runtab WHERE date_issue = $todate ORDER BY rnid DESC";
    $objSQL = new SQL($qr);
    $result = $objSQL->getResultOneRowArray();
    if (!empty($result)) {
        return $result['todayorderno'];
    } else {
        return 1;
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

