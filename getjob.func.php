<?php

function IsExistSchedule2102($quono, $noposition, $runningno, $bid) {
    $no = intval($noposition);
    $runno = intval($runningno);
    $schedule2102 = "production_scheduling_2102";
    $sqlschedule2102 = "SELECT COUNT(*) FROM $schedule2102 WHERE quono = '$quono' AND "
            . "noposition = $no AND runningno = $runno AND bid = $bid ";
    $objoutput = new SQL($sqlschedule2102);
    $resultoutput = $objoutput->getRowCount();

    if (empty($resultoutput) || !isset($resultoutput)) {
        $resultoutput = 0;
    }
    return $resultoutput;
}

function getRecordsetSche2102($quono, $noposition, $runningno, $bid) {

    $no = intval($noposition);
    $runno = intval($runningno);
    $schedule2102 = "production_scheduling_2102";
    $sqlschedule2102 = "SELECT * FROM $schedule2102 WHERE quono = '$quono' AND "
            . "noposition = $no AND runningno = $runno AND bid = $bid ";
    $objoutput = new SQL($sqlschedule2102);
    $resultoutput = $objoutput->getResultOneRowArray();
    if (empty($resultoutput) || !isset($resultoutput)) {
        $resultoutput = "no result";
    }
    return $resultoutput;
}

function IsExistSchedule2103($quono, $noposition, $runningno, $bid) {
    echo "\$quono = $quono, \$noposition = $noposition, \$runningno = $runningno , \$bid = $bid <br> ";
    $no = intval($noposition);
    $runno = intval($runningno);
    $schedule2103 = "production_scheduling_2103";
    $sqlschedule2103 = "SELECT COUNT(*) FROM $schedule2103 WHERE quono = '$quono' AND "
            . "noposition = $no AND runningno = $runno AND bid = $bid ";
    echo "in IsExistSchedule2103 , line 29\$sqlschedule2103 =  $sqlschedule2103 <br>";
    $objoutput = new SQL($sqlschedule2103);
    $resultoutput = $objoutput->getRowCount();
    echo "in function IsExistSchedule2103 \$resultoutput = $resultoutput <br>";
    if (empty($resultoutput) || !isset($resultoutput)) {
        $resultoutput = 0;
    }
    return $resultoutput;
}

function updatejobcodesid($jobcode, $period, $sid) {
    $sql = "UPDATE jobcodesid SET sid = $sid , period = $period "
            . "WHERE jobcode = '$jobcode' ";
    echo "\$sql = $sql <br>";
    $objSQL = new SQL($sql);
    $result = $objSQL->getUpdate();
    //$result = "not update to jobcodesid WHERE jobcode = $jobcode, period = $period AND sid = $sid<br>";
    return $result;
}

function checkThenUpdatePeriod($jobcode,$sid) {

    $period = substr($jobcode, 7, 4);
    $sql = "SELECT COUNT(*) FROM jobcodesid WHERE jobcode = '$jobcode'";
    // echo "\$sql = $sql <br>";
    $objSQL = new SQL($sql);
    $result = $objSQL->getRowCount();
    if (!empty($result)) {
        if ($result > 0) {
            $sql2 = "SELECT * FROM jobcodesid WHERE jobcode = '$jobcode'";
            $objSQL2 = new SQL($sql2);
            $resultsql2 = $objSQL2->getResultOneRowArray();

            $period_get = $resultsql2['period'];
            if ($period_get != $period) {
                //echo "\$period_get = $period_get , but \$period = $period <br>";
                $resultUpdate = updatejobcodesid($jobcode, $period, $sid);
                echo "\$resultUpdate = $resultUpdate <br>";
                $result = "Update period FROM $period_get to $period ";
            } else {
                //echo "\$period_get = $period_get is the same as \$period = $period <br>";
                $result = "no update of period";
            }
        } elseif ($result == 0) {

            $result = "no update of period,value is 0";
        }
    }

    return $result;
}

function checkSBOperation2($jobcode, $sid) {
    $period = substr($jobcod, 7, 4);

    $sql = "SELECT * FROM production_scheduling_" . $period . " WHERE sid = '$sid'";
    $objSql = new SQL($sql);
    $result = $objSql->getResultOneRowArray();
    $jlfor = $result['jlfor'];
    if ($jlfor == 'SB') {
        if (operation == 2 || operation == 1 || operation == 4) {

            $message = "set operatrion = 3 <br>";
        }
    } else if ($jlfor == 'CJ') {
        if (operation == 2 || operation == 3 || operation == 4) {
            $message = "set operatrion = 1 <br>";
        }
    }
    return $message;
}

function insBySqlOutput2102($sql) {

    $objSQL = new SQL($sql);
    $insResult = $objSQL->InsertData();
    return $insResult;
}

function IsExistOutput2102($sid) {

    $output2102 = "production_output_2102";
    $sqloutput2102 = "SELECT * FROM $output2102 WHERE sid = '$sid'";
    $objoutput = new SQL($sqloutput2102);
    $resultoutput = $objoutput->getResultRowArray();
    return $resultoutput;
}

function IsExistOutput2103($sid) {

    $output2103 = "production_output_2103";
    $sqloutput2103 = "SELECT * FROM $output2103 WHERE sid = '$sid'";
    echo "in function IsExistOutput2103 the sid is $sid <br>";
    echo "\$sqloutput2103 = $sqloutput2103 <br>";
    $objoutput = new SQL($sqloutput2103);
    $resultoutput = $objoutput->getResultRowArray();
    echo "<br> in IsExistOutput2103, var_dump resultoutput<br> ";
    var_dump($resultoutput);
    echo "<br>";
    return $resultoutput;
}

function insertToOutput2102($Insert_Array) {

    echo "<br> c<br>";
    var_dump($Insert_Array);
    echo "<br>";

    $qrins = "INSERT INTO $prodtab SET ";
    $qrins_debug = "INSERT INTO $prodtab SET ";
    $arrCnt = COUNT($Insert_Array);
    $cnt = 0;
    foreach ($Insert_Array as $key => $val) {
        $cnt++;
        $qrins .= " $key =:$key ";
        $qrins_debug .= " $key = '$val' ";
        if ($cnt != $arrCnt) {
            $qrins .= " , ";
            $qrins_debug .= " , ";
        }
    }

    echo "<br><br>\$qrins = $qrins <br><br>";
    echo "<br><br>\$qrins_debug= $$qrins_debug <br><br>";
    echo "<br>$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$<br>";

    $objSQLlog = new SQLBINDPARAM($qrins, $Insert_Array);
    $insResult = $objSQLlog->InsertData2();
    echo "===DEBUG LOG QR = $qrins_debug <br>";
    echo "+++===LOG RESULT = $insResult<br>";
    return $insResult;
}

function convertRunnoToString($runningno) {
    if (intval($runningno) < 1000) {

        if (intval($runningno) < 100) {

            if (intval($runningno) < 10) {
                $str_runno = "000" . strval($runningno);
            } elseif (intval($runningno) >= 10 && intval($runningno) < 100) {
                $str_runno = "00" . strval($runningno);
            } else {
                $str_runno = "error";
            }
        } elseif (intval($runningno) >= 100 && intval($runningno) <= 999) {
            $str_runno = "0" . strval($runningno);
        } else {

            $str_runno = "error";
        }
    } else {
        $str_runno = strval($runningno);
    }
    return $str_runno;
}

function deloutput2103($sid2) {
    $output2103 = "production_output_2103";
    $sql = "DELETE FROM $output2103 WHERE sid = $sid2 ";
    echo "\$sql = $sql <br>";
    $objSql = new SQL($sql);
    $result = $objSql->getDelete();
    //$result = "not delete FROM $output2103 WHERE sid = $sid2, for now <br> ";
    return $result;
}

function delSche2103($sid2) {
    $pro2103 = "production_scheduling_2103";
    $sql = "DELETE FROM $pro2103 WHERE sid = $sid2 ";
    echo "\$sql = $sql <br>";
    $objSql = new SQL($sql);
    $result = $objSql->getDelete();
    //$result = "not delete FROM $pro2103 WHERE sid = $sid2 , fo rnow <br>";
    return $result;
}

function checkJobcodeSidPeriod($jobcode) {
    $sql = "SELECT * FROM jobcodesid WHERE jobcode = '$jobcode'";
    $obj = new SQL($sql);
    $result = $obj->getResultOneRowArray();
    $period = $result['period'];
    return $period;
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * AND open the template in the editor.
 */

