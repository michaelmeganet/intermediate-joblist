<?php

include "./class/dbh.inc.php";
include "./class/variables.inc.php";

function IsExistSchedule2102($quono, $noposition ,$runningno, $bid) {
    $no = intval($noposition);
    $runno = intval($runningno);
    $schedule2102 = "production_scheduling_2102";
    $sqlschedule2102 = "select count(*) from $schedule2102 where quono = '$quono' AND "
            . "noposition = $no and runningno = $runno and bid = $bid ";
    $objoutput = new SQL($sqlschedule2102);
    $resultoutput = $objoutput->getRowCount();

    if(empty($resultoutput) || !isset($resultoutput)){
        $resultoutput = 0;
    }    
    return $resultoutput;


}
function getRecordsetSche2102($quono, $noposition ,$runningno, $bid){
    
    $no = intval($noposition);
    $runno = intval($runningno);
    $schedule2102 = "production_scheduling_2102";
    $sqlschedule2102 = "select * from $schedule2102 where quono = '$quono' AND "
            . "noposition = $no and runningno = $runno and bid = $bid ";
    $objoutput = new SQL($sqlschedule2102);
    $resultoutput = $objoutput->getResultOneRowArray();
    if(empty($resultoutput) || !isset($resultoutput)){
        $resultoutput = "no result";
    }    
    return $resultoutput;

    
}

function IsExistSchedule2103($quono, $noposition ,$runningno, $bid) {
    echo "\$quono = $quono, \$noposition = $noposition, \$runningno = $runningno , \$bid = $bid <br> ";
    $no = intval($noposition);
    $runno = intval($runningno);
    $schedule2103 = "production_scheduling_2103";
    $sqlschedule2103 = "select count(*) from $schedule2103 where quono = '$quono' AND "
            . "noposition = $no and runningno = $runno and bid = $bid ";
    echo "in IsExistSchedule2103 , line 29\$sqlschedule2103 =  $sqlschedule2103 <br>";
    $objoutput = new SQL($sqlschedule2103);
    $resultoutput = $objoutput->getRowCount();
    echo "in function IsExistSchedule2103 \$resultoutput = $resultoutput <br>";
    if(empty($resultoutput) || !isset($resultoutput)){
        $resultoutput = 0;
    }
    return $resultoutput;


}
function updatejobcodesid($jobcode, $period, $sid){
    $sql = "UPDATE jobcodesid SET sid = $sid , period = $period "
            . "WHERE jobcode = '$jobcode' ";
    echo "\$sql = $sql <br>";
    $objSQL = new SQL($sql);
    $result = $objSQL->getUpdate();
     //$result = "not update to jobcodesid where jobcode = $jobcode, period = $period and sid = $sid<br>";
    return $result;
}
function checkThenUpdatePeriod($jobcode){
    
    $period = substr($jobcode, 7,4);
    $sql = "select count(*) from jobcodesid where jobcode = '$jobcode'";
   // echo "\$sql = $sql <br>";
    $objSQL = new SQL($sql);
    $result = $objSQL->getRowCount();
    if(!empty($result)){
        if($result > 0){
            $sql2 = "select count(*) from jobcodesid where jobcode = '$jobcode'";
            $objSQL2 = new SQL($sql2);
            $resultsql2 = $objSQL2->getResultOneRowArray();

            $period_get = $resultsql2['period'];
            if($period_get != $period){
                //echo "\$period_get = $period_get , but \$period = $period <br>";
                $resultUpdate =  updatejobcodesid($jobcode, $period, $sid);
                echo "\$resultUpdate = $resultUpdate <br>";
                $result = "Update period from $period_get to $period ";
                
            }else{
                //echo "\$period_get = $period_get is the same as \$period = $period <br>";
                $result = "no update of period";
            }
            
        }elseif ($result ==0) {
            
            $result = "no update of period,value is 0";
            
        }
    }

    return $result;
    
}
function checkSBOperation2($jobcode,$sid) {
    $period = substr($jobcod, 7,4);
    
    $sql = "select * from production_scheduling_".$period." where sid = '$sid'";
    $objSql = new SQL($sql);
    $result = $objSql->getResultOneRowArray();
    $jlfor = $result['jlfor'];
    if($jlfor == 'SB'){
        if(operation == 2 || operation == 1 || operation == 4){
            
            $message = "set operatrion = 3 <br>";
       
        }
    }else if($jlfor == 'CJ'){
        if(operation == 2 || operation == 3 || operation == 4){
            $message =  "set operatrion = 1 <br>";
       
        }
    }
    return $message;
    
}
function insBySqlOutput2102($sql){
    
     $objSQL = new SQL($sql);
     $insResult = $objSQL->InsertData();
     return $insResult;

}
function IsExistOutput2102($sid) {

    $output2102 = "production_output_2102";
    $sqloutput2102 = "select * from $output2102 where sid = '$sid'";
    $objoutput = new SQL($sqloutput2102);
    $resultoutput = $objoutput->getResultRowArray();
    return $resultoutput;


}



function IsExistOutput2103($sid) {

    $output2103 = "production_output_2103";
    $sqloutput2103 = "select * from $output2103 where sid = '$sid'";
    echo "in function IsExistOutput2103 the sid is $sid <br>";
    echo "\$sqloutput2103 = $sqloutput2103 <br>";
    $objoutput = new SQL($sqloutput2103);
    $resultoutput = $objoutput->getResultRowArray();
    echo "<br> in IsExistOutput2103, var_dump resultoutput<br> ";
    var_dump($resultoutput);
    echo "<br>";
    return $resultoutput;


}

function insertToOutput2102($Insert_Array){
    
            echo "<br> c<br>";
            var_dump($Insert_Array);
            echo "<br>";

            $qrins = "INSERT INTO $prodtab SET ";
            $qrins_debug = "INSERT INTO $prodtab SET ";
            $arrCnt = count($Insert_Array);
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
function convertRunnoToString($runningno){
    if (intval($runningno) < 1000){

    if (intval($runningno)< 100){

        if (intval($runningno)< 10){
             $str_runno = "000".strval($runningno);
        }elseif (intval($runningno)>= 10 && intval($runningno) < 100){
            $str_runno = "00".strval($runningno);
        }else{
            $str_runno = "error";
        }

    } elseif (intval($runningno)>= 100 && intval($runningno)<= 999 ) {
         $str_runno = "0".strval($runningno);
    }else{

         $str_runno = "error";

    }

    }else{
    $str_runno = strval($runningno);
    }
    return $str_runno;
}
function deloutput2103($sid2){
    $output2103 = "production_output_2103";
    $sql = "DELETE FROM $output2103 WHERE sid = $sid2 ";
    echo "\$sql = $sql <br>";
    $objSql = new SQL($sql);
    $result = $objSql->getDelete();
    //$result = "not delete from $output2103 where sid = $sid2, for now <br> ";
    return $result;
}

function delSche2103($sid2){
    $pro2103 = "production_scheduling_2103";
    $sql = "DELETE FROM $pro2103 WHERE sid = $sid2 ";
    echo "\$sql = $sql <br>";
    $objSql = new SQL($sql);
    $result = $objSql->getDelete();
    //$result = "not delete from $pro2103 where sid = $sid2 , fo rnow <br>";
    return $result;
    
}
function checkJobcodeSidPeriod($jobcode){
    $sql = "SELECT * FROM jobcodesid WHERE jobcode = '$jobcode'";
    $obj = new SQL($sql);
    $result = $obj->getResultOneRowArray();
    $period = $result['period'];
    return $period;
    
}
$pro2102 = "production_scheduling_2102";
$pro2103 = "production_scheduling_2103";
$output2102 = "production_output_2102";
$output2103 = "production_output_2103";
$sql2102 = "select * from $pro2102 where operation = 1 or  operation = 3 order by quono ";
$sql2103 = "select * from $pro2103 where operation = 1 or  operation = 3  order by quon";

$objsql2102 = new SQL($sql2102);

$result1 = $objsql2102->getResultRowArray();
//
//print_r($result1);
$totalcount = 0;
$delcount = 0;
$jcudpatecount = 0;
foreach ($result1 as $array){
    $totalcount++;
//    print_r($array);
    $quono = $array['quono'];

    $bid = $array['bid'];
    $sid = $array['sid'];
    $quono = $array['quono'];
    $qid = $array['qid'];
    $runningno = $array['runningno'];
    $noposition = $array['noposition'];
    $jlfor = $array['jlfor'];
    $date_issue = $array['date_issue'];
    $year = substr($date_issue,2,2);
    $month = substr($date_issue,5,2);
    $period = $year.$month; // from table $pro2102 production_scheduling_2102
    $co_code = substr($quono,0,3);
    #####################################################
    if($noposition < 10){
        $no = "0".strval($noposition);
    }elseif($noposition >= 10){
        $no = strval($noposition);
    }
    $str_runno = convertRunnoToString($runningno);


    $jobcode = $jlfor." ".$co_code." ".$period." ".$str_runno." ".$no;
    #########################################################################
    echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^<br>";
    echo " The jobcode is $jobcode <br>";
    echo " The quono is $quono, runningno = $runningno, bid = $bid, noposition = $noposition <br>";
    echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^<br>";
    
    $checkSch2102 = IsExistSchedule2102($quono, $noposition ,$runningno, $bid);
    $checkSch2103 = IsExistSchedule2103($quono, $noposition ,$runningno, $bid);

   echo "\$checkSch2102 = $checkSch2102 , \$checkSch2103 = $checkSch2103 <br>";
    if($checkSch2102 > 0 ){// $checkSch2102 > 0
        echo "!!!!!!!! \$checkSch2102 > 0 !!!!!!!! (if) <br>";
        echo "Line 282, \$checkSch2102 = $checkSch2102 ;,there is $checkSch2102 record in Sch2102 <br>";
        
        if($checkSch2103 > 0){//$checkSch2102 > 0 and $checkSch2103 > 0
             echo "!!!!!!!! \$checkSch2102 > 0 and \$checkSch2103 > 0 !!!!!!!! (if) <br>";
            
             echo "Line 285, \$checkSch2103 = $checkSch2103 ,there is $checkSch2103 record in Sch2103 <br>";
            $sqlfind = "select sid, quono, qid, runningno, noposition  from $pro2103 "
            . "WHERE quono = '$quono' and qid = '$qid' and runningno = '$runningno' AND bid = $bid "
            . "AND noposition = '$noposition' ";
        echo " \$sqlfind =  $sqlfind <br>";
        $objsqlfind = new SQL($sqlfind);

        $checkResult = checkThenUpdatePeriod($jobcode);
        echo "The check result of period in $jobcode  = ".$checkResult."<br>";
        $found = $objsqlfind->getResultOneRowArray();
        #$resultSB = checkSBOperation2($jobcode,$sid);
        //echo "\$resultSB  = $resultSB <br>";
        if(empty($found)){
            echo "<br> not found match of $quono, with qid = $qid , runningno = $runningno <br>";
        }else{
            echo "====== AT $pro2102 ===found match=====<BR> ";
            echo "sid = $sid, quono =  $quono, runningno = $runningno , noposition = $noposition, jlfor - $jlfor <br> ";
            echo "======END $pro2102 ========<BR> ";
            echo "there is result in $pro2103 <br>";
            echo " \$sqlfind =  $sqlfind<br>";
            $sid2 = $found['sid'];
    //        $quono2 = $objsqlfind['quono'];
            echo "<br>################################################################################################<br>";
            echo "date_issue = $date_issue ,year = $year, month = $month , period = $period , co_code = $co_code<br>";
            echo "sid2 in $pro2103 is $sid2 <br>";
            echo "<br> jobcode = $jobcode<br>";
            $sqlJc = "select * from jobcodesid where jobcode = '$jobcode'";
            $sqlJc = new SQL($sqlJc);
            $joblistno = $sqlJc->getResultOneRowArray();
            if(empty($joblistno)){

                echo "no jobcode found in table jobcodesid, the joblist not yet being scanned<br>";
                //check production_output_period
                $checkResult2102 = IsExistOutput2102($sid);
    //            $sqloutput2102 = "select * from $output2102 where sid = '$sid'";
    //            $objoutput1 = new SQL($sqloutput2102);
    //            $resultoutput1 = $objoutput1->getResultRowArray();
                $resultoutput1 = $checkResult2102 ;
                echo "<br>------------$output2102-------------------------<br>";
                if(empty($resultoutput1)){
                    echo "no result on $sqloutput2102 <br>";

                }else{
                     echo "there are result on $sqloutput2102 <br>";
                     var_dump($resultoutput1);
                     echo"<br>";
                }
                echo "<br>------------$output2103--------------------------------<br>";
                $checkResult2103 =IsExistOutput2103($sid2);
                if($checkResult2103 == 'no result on getResultRowArray'){
                    echo "checkResult2103 is no result on getResultRowArray <br>"
                    . "there are no result on function IsExistOutput2103 <br>";
                }else{
                    echo "checkResult2103 is having result on getResultRowArray <br>"
                    . "there are  result on function IsExistOutput2103 <br>";
                }
    //            $sqloutput2103 = "select * from $output2103 where sid = '$sid2'";
    //            $objoutput2 = new SQL($sqloutput2103);
    //            $resultoutput2 = $objoutput2->getResultRowArray();
                $resultoutput2 = $checkResult2103;
                if($resultoutput2 != 'no result on getResultRowArray'){
    //                echo "\$resultoutput2 = $resultoutput2 <br>";
    //                print_r($resultoutput2);
    //                echo "<br>";
                     echo " Line 192, resultoutput2 is not no result on getResultRowArray <br>"
                    . "there are result on $sqloutput2103<br>";
                     var_dump($resultoutput2);
                     echo"<br>";
                }else{
                    echo "Line 197 resultoutput2 is no result on getResultRowArray <br>"
                    . "there are no result on $sqloutput2103<br>";
                    echo "\$resultoutput2 = $resultoutput2 <br>";
                    echo "\$sqloutput2103 = $sqloutput2103  <br>";
                    echo "no result on $sqloutput2103 <br>";
                    $delResultSche2103 = delSche2103($sid2);

                    $delcount++;
                    if($delResultSche2103 == 'deleted'){
                        echo "$pro2103 where sid = $sid2 had been  $delResultSche2103 <br>";
                    }else{
                        echo "$pro2103 where sid = $sid2 had been  $delResultSche2103 <br>";
                    }
                    unset($delResultSche2103);


                }

                if(!empty($resultoutput1) && (empty($resultoutput2))){
                    echo "1st rectification of table jobcodesid  found emtpy, and $sqloutput2103 found  empty <br>";
                    echo "$sqloutput2102 have result, and $sqloutput2103 is return empty<br>";
                    echo "remove the record where sid = $sid2 in $pro2103 <br> ";
                    $sqldel = "DELETE FROM $pro2103 where sid = $sid2";
                    echo "\$sqldel = $sqldel <br>";
                    $delcount++;
                    $objdele = new SQL($sqldel);
                    $resultdel = $objdele->getDelete();
                    echo "$resultdel <br>";
                    unset($objdele);
                    unset($resultdel);
                } elseif (empty($resultoutput1) && empty($resultoutput2)) {

                    echo "2nd rectification of table jobcodesid  found emtpy,<br>"
                    . " and $sqloutput2103 found  empty  with $sqloutput2102 also found empty<br>";
                    $sqldel = "DELETE FROM $pro2103 where sid = $sid2";
                    echo "\$sqldel = $sqldel <br>";
                    $delcount++;
                    $objdele = new SQL($sqldel);
                    $resultdel = $objdele->getDelete();
                    echo "$resultdel <br>";
                    unset($objdele);
                    unset($resultdel);
                }


            }else{
                echo "var_dump of joblistno record in jobcodesid <br>";
                var_dump($joblistno);
                echo "<br>";
                echo "<br>====START THE CHECK THE PERIOD STORE IN jobcodesid for $jobcode====<br>";
                $periodsid =   $joblistno['period'];
                $jobcode =  $joblistno['jobcode'];
                echo "Line 258 \$jobcode = $jobcode <br>";

                echo "the record in table of $period , mean in production_scheduling_$period , "
                        . "the period store in jobcodesid is $periodsid <br>";
                $LOGICAL =strcmp($periodsid,$period);// = 0 IF SAME INDENTICAL
                if ($LOGICAL == 0){
                    echo "the period ($period) vs periodsid ($periodsid)  is identical<br>";


                    // check output2103 any record found?
                    $checkresult = IsExistOutput2103($sid2);
                    if($checkresult == 'no result on getResultRowArray'){
                        echo "Line 257,  \$checkresult = $checkresult <br>";
                        $delResultSche2103 = delSche2103($sid2);
                        $delcount++;
                        echo "\$delResultSche2103 = $delResultSche2103 <br>";
                        unset($delResultSche2103);
                    }else{
                        echo "<br> print_r $checkresult <br>";
                            print_r($checkresult);
                            echo "<br>";
                    }
                    if($checkresult != 'no result on getResultRowArray'){
                        $delcount++;
                        foreach($checkresult as $array){
                            $poid = '';
                            $jobtype = $array['jobtype'];
                            $date_start_str = $array['date_start'];

                            if($date_start_str == '' || !isset($date_start_str) || empty($date_start_str) ){
                                $date_start = NULL;
                            }else{
                                $date_start_value =  strtotime($date_start_str);
                                $date_start = date('Y-m-d h:i:s', $date_start_value);
                            }

                            $start_by = $array['start_by'];
                            $machine_id = $array['machine_id'];
                            $date_end_str = $array['date_end'];

                            if($date_endt_str == '' || !isset($date_end_str) || empty($date_end_str)  ){
                                $date_end = NULL;
                            }else{
                                $date_end_value =  strtotime($date_end_str);
                                $date_end = date('Y-m-d h:i:s', $date_end_value);
                            }

                            $end_by = $array['end_by'];
                            $quantity = $array['quantity'];
                            $totalquantity = $array['totalquantity'];
                            $remainingquantity = $array['remainingquantity'];
                            echo "jobtype = $jobtype, date_start = $date_start, "
                                    . " start_by = $start_by, machine_id = $machine_id, "
                                    . "date_end = $date_end, end_by  = $end_by , "
                                    . "quantity = $quantity, totalquantity = $totalquantity,remainingquantity = $remainingquantity<br>";
                            /*$Insert_Array = array(
                                'poid' =>$poid,
                                'sid' => $sid,
                                'jobtype' => $jobtype,
                                'date_start' => $date_start,
                                'start_by' => $start_by,
                                'machine_id' => $machine_id,
                                'date_end' => $date_end,
                                'end_by' => $end_by,
                                'quantity' => $quantity,
                                'totalquantity' => $totalquantity,
                                'remainingquantity' => $remainingquantity

                            );*/
                            $sqlinsert1 = "INSERT INTO $output2102 (poid, sid, jobtype,"
                                    . " date_start, start_by, machine_id, date_end, "
                                    . "end_by, quantity, totalquantity,remainingquantity )  VALUES"
                                    . "(NULL, $sid, '$jobtype', '$date_start', "
                                    . "'$start_by', '$machine_id', '$date_end', '$end_by', "
                                    . "$quantity,$totalquantity,"
                                    . "$remainingquantity)";
                            echo "\$sqlinsert1 = $sqlinsert1 <br>";

                            $insertResult = insBySqlOutput2102($sqlinsert1);
                            echo "The insertionresult is $insertResult <br>";

                            //delete from production_output_2103 where sid = $sid2
                            $delResultOutput2103 = deloutput2103($sid2);
                            echo "Line 341 \$delResultOutput2103 = $delResultOutput2103 <br>";
                            unset($delResultSche2103);
                            //delere from production_scheduling_2103 where sid = $sid2;
                            $delResultSche2103 = delSche2103($sid2);
                            echo "Line 345 \$delResultSche2103 = $delResultSche2103 <br>";
                            unset($delResultSche2103);

                        }
                    }else{
                        echo "Line 337 $checkresult <br>";
                    }
                    //if found then copy the records, insert to output2102
                    // then delete all records in output2103

                }else{
                    echo "the period ($period) vs periodsid ($periodsid)  is not identical<br>";

                    // update jobcodesid such that the period = = $period (2102)
                    // and the sid = $sid, not $sid2
                    $resultUpdate = updatejobcodesid($jobcode,$period, $sid);
                    $jcudpatecount++;
                    echo "update the jobcodesid table for $joblistno with period = $period and sid = $sid"
                            . " the result is $resultUpdate <br>";
                    //if found some things in output2103 then isert into output2102
                    $checkResult2103 =IsExistOutput2103($sid2);
                    echo "Line 366 <br>";
                    if($checkResult2103 == 'no result on getResultRowArray'){
                        echo "checkResult2103 is no result on getResultRowArray <br>"
                        . "there are no result on function IsExistOutput2103 <br>";
                    }else{
                        echo "checkResult2103 is having result on getResultRowArray <br>"
                        . "there are  result on function IsExistOutput2103 <br>";
                    }


                    // then delete the records in output2103
                    // delete the records in schedule2103

                }
                echo "<br>====END OF CHECK THE PERIOD STORE IN jobcodesid for $jobcode=======<br>";
            }
            echo "<br>^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^<br>";
    //        echo "checking in $pro2102 ,<br> sid = $sid , quono =  $quono , qid = $qid , <br>"
    //                . " runningno = $runningno , noposition = $noposition <br>";
    //        echo "<br> found match in $pro2102 <br> sid = $sid , quono =  $quono, with qid = $qid , <br>"
    //                . "runningno = $runningno , noposition = $noposition  <br>";
            echo "<br>$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$<br>";
        }
        unset($objsqlfind);

        }elseif ($checkSch2103 == 0) { //$checkSch2102 > 0 and $checkSch2103 == 0
            echo "!!!!!!!! \$checkSch2102 > 0 and \$checkSch2103 == 0 !!!!!!!! (elseif) <br>";
            echo "<br>^^^^Case \$checkSch2103 == 0 but ^^^^^\$checkSch2102 = $checkSch2102^^^<br>";
            echo "Line 538, in \checkSch2103 == $checkSch2103, \$checkSch2102 = $checkSch2102 , not found  and 2103 records but found in  2102<br>";
            echo "<br>^^^^^^^^^Start recitifcation ^^^^^^^^^^^^<br>";
            
            // check jobcodesid, the period is what
            $thePeriod =  checkJobcodeSidPeriod($jobcode);
            echo "\$period = $period , \$thePeriod = $thePeriod <br>";
            $intPeriod = intval($period);
            $intThePeriod = intval($thePeriod);
            echo "\$intPeriod = $intPeriod, \$intThePeriod = $intThePeriod <br>";
            $netPeriod = abs($intThePeriod - $intPeriod);
            if ($netPeriod == 0){
                // both period are the same, do nothing
                echo "both period \$period and \$thePeriod are the same, do nothing <br>";
            }elseif($netPeriod > 0){
                // the netPeriod is greater than 0
                $valuenet = $intThePeriod - $intPeriod;
                if ($valuenet > 0) {
                    // $intThePeriod >  $intPeriod
                    //perod 2103 > 2102 and sche2102 is exist , but 2103 sche2103 not exist
                    //$thePeriod is the period store in jobcodesid, which link to sid and period of scheduling and output
                    //$thePeriod is 2013 store in jobcodesid, but sche2013 do notr exist, only schedule 2012 exist
                    //
                    
                    //update jobcodesid period = $period
                    $sqlcheck = "SELECT * FROM jobcodesid WHERE jobcode = '$jobcode' ";
                    $objCheck = new SQL($sqlcheck);
                    $resultCheck = $objCheck->getResultOneRowArray();
                    $checkSid = "";
                    $checkSid = $resultCheck['sid'];
                    $checkPeriod = $resultCheck['period'];
                    if ($checkSid == $sid ){
                        // $checkSid == $sid
                        echo "\$checkSid = \$sid , the answer is $sid <br>";
                        
                        if ($checkPeriod == $period){
                            // $checkSid == $sid and $checkPeriod == $period
                            echo "\$checkPeriod = \$period , the answer is $period <br>";
                            
                        }else{
                            // $checkSid == $sid and $checkPeriod != $period
                            echo "\$checkPeriod != \$period , the\$checkPeriod = $checkPeriod ,   the \$period is $period <br>";
   
                            
                            
                        }
                        
                    }else{
                        // $checkSid != $sid
                        echo "\$checkSid != \$sid , the\$checkSid = $checkSid ,   the \$sid is $sid <br>";
                        
                        if ($checkPeriod == $period){
                            // $checkSid != $sid and $checkPeriod == $period
                            echo "\$checkPeriod = \$period , the answer is $period <br>";
                            
                        }else{
                            // $checkSid != $sid and $checkPeriod != $period
                            echo "the next step  is to update the jobcodesid with sql query \$sqlUpdate <br> ";
                            echo "\$checkPeriod != \$period , the\$checkPeriod = $checkPeriod ,   the \$period is $period <br>";
                            echo "The actual period storing in jobcodesid table is $checkPeriod , "
                                    . "the \$checkPeriod, the period from prod2102 is \$period , $period <br> ";
                            echo "So $checkPeriod is not the correct period that"
                                    . " have to be stored in jobcodedsid,<br>  "
                                    . "update period column of $jobcode in jobcodesid by \$period = $period "
                                    . "<br> and the sid value update by sid, $sid <br> ";
                            $sqlUpdate = "UPDATE jobcodesid set sid = '$sid' , period = '$period' WHERE jobcode = '$jobcode'";
                            echo "<br> \$sqlUpdate = $sqlUpdate <br>";
                            
                            $objUpdate = new SQL($sqlUpdate);
                            $resultUpdate = $objUpdate->getUpdate();
                            echo "update result = $resultUpdate <br>";
                            $ResultSche2102  = getRecordsetSche2102($quono, $noposition ,$runningno, $bid);
                            $getSche2012_sid = $ResultSche2102['sid'];
                            $getSche2012_quono = $ResultSche2102['quono'];
                            $getSche2012_noposition = $ResultSche2102['noposition'];
                            $getSche2012_bid = $ResultSche2102['bid'];
                            $getSche2012_runningno = $ResultSche2102['runningno'];
                            echo "\$getSche2012_sid  = $getSche2012_sid , \$getSche2012_quono =  $getSche2012_quono , "
                                 . "\$getSche2012_noposition = $getSche2012_noposition, \$getSche2012_bid = $getSche2012_bid , "
                                    . "\$getSche2012_runningno = $getSche2012_runningno <br>";
                            
                            if ($sid == $getSche2012_sid) {
                                // $sid = $getSche2012_sid
                                echo " Line 643, \$sid = \$getSche2012_sid <br>";
                                
                                if ($quono == $getSche2012_quono) {
                                    //  $quono = $getSche2012_quono
                                    echo "Line 647, \$quono = \$getSche2012_quono <br>";
                                    
                                    if($noposition == $getSche2012_noposition) {
                                        // $noposition = $getSche2012_noposition
                                        echo "Line 651 , \$noposition =\$getSche2012_noposition <br>";
                                        if ($bid ==$getSche2012_bid ) {
                                            //$bid ==$getSche2012_bid 
                                            echo "Line 654 , \$bid =\$getSche2012_bid <br>";
                                            echo "\$runningno = $runningno , \$getSche2012_runningno = $getSche2012_runningno <br>";
                                            if ($runningno == $getSche2012_runningno ) {
                                                //$runingno == $getSche2012_runningno
                                                
                                                echo "$pro2102 data is match with jobcodesid and self check it correct <br>";
                                                echo "**** No Need do any update on $pro2102<br>";
                                                $checkOuput2103 =  IsExistOutput2103($checkSid);
                                                if($checkOuput2103 != "no result on getResultRowArray"){
                                                    echo "There are no result on Ouput2103, the result is correct.<br>";
                                                }else{
                                                     echo "There are still have result on Ouput2103, the result is in correct.<br>";
                                                     //check the data is related to out sid and period and quotation no, and noposition
                                                        
                                                     ## if the data is related to the quotation, period sid and noposition
                                                     ## then move the record to poutput2102
                                                     ## else do nothings (because this is not the correct record to be moved
                                                     
                                                     echo "move record from  output2103<br>";
                                                    $sqlinsert2 = "INSERT INTO $output2102 (poid, sid, jobtype,"
                                                        . " date_start, start_by, machine_id, date_end, "
                                                        . "end_by, quantity, totalquantity,remainingquantity )  VALUES"
                                                        . "(NULL, $sid, '$jobtype', '$date_start', "
                                                        . "'$start_by', '$machine_id', '$date_end', '$end_by', "
                                                        . "$quantity,$totalquantity,"
                                                        . "$remainingquantity)";
                                                    echo "\$sqlinsert2 = $sqlinsert2 <br>";
                                                    ## $insertResult2 = insBySqlOutput2102($sqlinsert2);
                                                    ##echo "The insertionresult2 is $insertResult2 <br>";

                            $insertResult = insBySqlOutput2102($sqlinsert1);
                            echo "The insertionresult is $insertResult <br>";
                                                     
                                                     $resultDel1 = deloutput2103($checkSid);
                                                     echo "\resultDel1 = $resultDel1 <br>";
                                                     
                                                }
                                                
                                                
                                            }else{
                                                //$runingno != $getSche2012_runningno
                                                echo "\$runningno != \$getSche2012_runningno <br> "
                                                . "Line 664 , \$runningno = $runningno ,\$getSche2012_runningno = $getSche2012_runningno <br> ";
                                                
                                            }
                                            
                                        }else{
                                           // $bid ! =$getSche2012_bid 
                                            echo "\$bid ! = \$getSche2012_bid <br>";
                                        }
                                        
                                    }else{// end else (if $noposition == $getSche2012_noposition )
                                        //$noposition != $getSche2012_noposition
                                        echo "\$noposition !=\ $getSche2012_noposition";
                                    }
                                    
                                }else{//$quono != $getSche2012_quono
                                    
                                    echo "\$quono != \$getSche2012_quono  <br>";
                                    echo "\$quono = $quono , \$getSche2012_quono =$getSche2012_quono <br>"; 
                                }
                                
                            }else{//$sid != $getSche2012_sid
                                echo "\$sid != \$getSche2012_sid  <br>";
                            }//endif $sid == $getSche2012_sid
                            
                            
                        }
                    }
//                    echo "period = $period, noposition = $noposition,  runningno = $runningno, bid = $bid <br>";
//                    $sqlUpdate = "UPDATE jobcodesid set sid = '$sid' , period = '$period' WHERE jobcode = '$jobcode'";
//                    $objUpdate = new SQL($sqlUpdate);
//                    $resultUpdate = $objUpdate->getUpdate();
//                    echo "update result = $resultUpdate <br>";
                }
            }
            echo "<br>^^^^^^^^End of recitifcation ^^^^^^^<br>";
            
        }//endif  $checkSch2102 > 0,  
        echo "!!!!!!!! \$checkSch2102 > 0  !!!!!!!! (endif) <br>";
    }elseif($checkSch2102 == 0){//not found any records in Sch2102
        //$checkSch2102 == 0
        echo "!!!!!!!! \$checkSch2102 == 0  !!!!!!!! (elseif) <br>";
        //echo "!!!!!!!! \$checkSch2102 == 0 and \$checkSch2103 == 0 !!!!!!!! (if) <br>";
        
        echo "Line 546, in \checkSch2102 == $checkSch2102,  not found any record in Sch2102 records <br>";
        if($checkSch2103 > 0){//but found some record in Sch2103
            //$checkSch2102 == 0 and $checkSch2103 > 0
            echo "!!!!!!!! \$checkSch2102 == 0 and \$checkSch2103 > 0 !!!!!!!! (if) <br>";
            echo "found records in Sch2103 \$checkSch2102 == 0   \$checkSch2103 = $checkSch2103   at else if loop<br>";
            //check the jobcodesid, see period is 2102 or 2103
            //if  period is 2103 then the records in in 2103 schedule and output
            $JobcodeSidPeriod = checkJobcodeSidPeriod($jobcode);
            
            if($JobcodeSidPeriod == '2103'){
                echo "This jobcode $jobcode is fall in period 2103 <br>";
            }else{
                echo "This jobcode $jobcode is fall in period 2102 <br>";
            }
            
            
        }elseif($checkSch2103 == 0){
         //$checkSch2102 == 0 and $checkSch2103 == 0
             echo "!!!!!!!! \$checkSch2102 == 0 and \$checkSch2103 == 0 !!!!!!!! (elseif) <br>";
            echo "for this jobcode  $jobcode, quono = $quono, noposition = "
                    . "$noposition, runningno = $runningno there are not records "
                    . "found in $pro2103 and $pro2102 <br>s"; 
        }else {
             //$checkSch2102 == 0 and $checkSch2103 < 0 
            echo "!!!!!!!! \$checkSch2102 == 0 and \$checkSch2103 < 0 !!!!!!!! (else) <br>";
            echo " quono = $quono, noposition = "
                    . "$noposition, runningno = $runningno have error <br>";
        }//endif $checkSch2103
        
    }else{
        //$checkSch2102 < 0
        echo "!!!!!!!! \$checkSch2102 < 0  !!!!!!!! (else) <br>";
        echo "\$checkSch2102 = $checkSch2102 , error <br>";
    }
    

    
    
}
unset($result1);
echo "total delete record is $delcount <br>";
echo "total count record is $totalcount <br>";
echo "total jobcodesid update count is $jcudpatecount <br>";