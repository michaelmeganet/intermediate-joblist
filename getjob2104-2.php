<?php

include "./class/dbh.inc.php";
include "./class/variables.inc.php";

function  check_jobcodesid($jobcode){
    
    $sql = "SELECT count(*) FROM jobcodesid WHERE jobcode = '$jobcode'";
    $objSql = new SQL($sql);
    $result = $objSql->getRowCount();
    return $result;
}

function getJobcodeDetail($jobcode){
    $sql = "SELECT * FROM jobcodesid WHERE jobcode = '$jobcode'";
    echo "Line 16 , \$sql = $sql <br>";
    $objSql = new SQL($sql);
    $result = $objSql->getResultOneRowArray();
    return $result;
}

function getSidSche2104($qid, $quono,$noposition, $bid){
    
    $Schedule = "production_scheduling_2104";
    $sqlschedule = "select sid from $Schedule where qid = '$qid'"
            . "AND quono = '$quono' AND noposition = $noposition AND bid = $bid";
    echo "<br> <strong> sqlschedule = $sqlschedule </strong> <br>";
    $objsche = new SQL($sqlschedule);
    $resultoutput = $objsche->getResultOneRowArray();
    $sid = $resultoutput['sid'];
    return $sid;
    
}

function check_date_issue_period($date_issue){
    // 2021-04-30
    $year = substr($date_issue, 2,2);
    $month =  substr($date_issue, 5,2);
    $period = "$year$month";
    return $period;
}
function updatejobcodesid($jobcode, $period, $sid){
    $sql = "UPDATE jobcodesid SET sid = $sid , period = $period "
            . "WHERE jobcode = '$jobcode' ";
    echo "In Line 44, \$sql = $sql <br>";
    $objSQL = new SQL($sql);
    $result = $objSQL->getUpdate();
    return $result;
}
function insBySqlOutput2103($sql){
    
     $objSQL = new SQL($sql);
     $insResult = $objSQL->InsertData();
     return $insResult;
//     if ($insResult == 'insert ok!') { //if insert succesful
//         
//         return $insResult;
//     }else{
////         throw new Exception("<font style='color:red'>can't insert.</font>", 102);
//         return $insResult;
//     }
    
}
function IsExistOutput2103($sid) {

    $output2103 = "production_output_2103";
    $sqloutput = "select * from $output2103 where sid = '$sid'";
    echo "in function IsExistOutput2103 the sid is $sid <br>";
    echo "\$sqloutput = $sqloutput <br>";
    $objoutput = new SQL($sqloutput);
    $resultoutput = $objoutput->getResultRowArray();
    echo "<br> in IsExistOutput2103, var_dump resultoutput<br> ";
    var_dump($resultoutput);
    echo "<br>";
    return $resultoutput;


}


function IsExistScheduling2103($qid,$quono,$noposition,$bid) {

    $Schedule2103 = "production_scheduling_2103";
    $sqlschedule2103 = "select * from $Schedule2103 where qid = '$qid'"
            . "AND quono = '$quono' AND noposition = $noposition AND bid = $bid";
    $objsche = new SQL($sqlschedule2103);
    $resultoutput = $objsche->getResultOneRowArray();
    return $resultoutput;


}
function IsExistScheduling2104($qid,$quono,$noposition,$bid) {

    $Schedule2104 = "production_scheduling_2104";
    $sqlschedule2104 = "select * from $Schedule2104 where qid = '$qid'"
            . "AND quono = '$quono' AND noposition = $noposition AND bid = $bid";
    echo "<br> <strong> sqlschedule2104 = $sqlschedule2104 </strong> <br>";
    $objsche = new SQL($sqlschedule2104);
    $resultoutput = $objsche->getResultOneRowArray();
    return $resultoutput;


}
function IsExistOutput2104($sid) {

    $output = "production_output_2104";
    $sqloutput = "select * from $output where sid = '$sid'";
    echo "<p style='color:red;background-color:white;'>in function IsExistOutput2104 the sid is $sid <br>";
    echo "\$sqloutput = $sqloutput <br>";
    $objoutput = new SQL($sqloutput);
    $resultoutput = $objoutput->getResultRowArray();
    echo "<br> in IsExistOutput2104, var_dump resultoutput<br> ";
    var_dump($resultoutput);
    echo "</p><br>";
    return $resultoutput;


}

function insertToOutput2103($Insert_Array){
    
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

function deloutput2104($sid_nextperiod){
    $output_nextperiod = "production_output_2104";
    $sql = "DELETE FROM $output_nextperiod WHERE sid = $sid_nextperiod ";
   
    echo "\$sql = $sql <br>";
    $objSql = new SQL($sql);
    $result = $objSql->getDelete();
    return $result;
}




$pro2103 = "production_scheduling_2103";
$pro2104 = "production_scheduling_2104";
$output2103 = "production_output_2103";
$output2104 = "production_output_2104";
$sql2103 = "select * from $pro2103 where operation = 1 or  operation = 3 order by sid DESC limit 1000";
//$sql2103 = "select * from $pro2103 where operation = 1 or  operation = 3 order by sid DESC limit 1000 offset 7000";
//$sql2103 = "select * from $pro2103 where runningno = 2401 and noposition = 8 order by sid DESC";
$sql2104 = "select * from $pro2104 where operation = 1 or  operation = 3  order by quon";

$objsql2103 = new SQL($sql2103);
echo "\$sql2103 = $sql2103 <br>";
$result1 = $objsql2103->getResultRowArray();
//
//print_r($result1);
$totalcount = 0;
$delcount = 0;
$jcudpatecount = 0;
$foundcount = 0;
foreach ($result1 as $array){
    $totalcount++;
    echo "<p style='color:green;background-color:white;'>now processing record no $totalcount, sid = $sid <br>";
//    print_r($array);
    $sid = $array['sid'];
    $quono = $array['quono'];
    $qid = $array['qid'];
    $runningno = $array['runningno'];
    $noposition = $array['noposition'];
    $jlfor = $array['jlfor'];
    
    $date_issue = $array['date_issue'];
    $year = substr($date_issue,2,2);
    $month = substr($date_issue,5,2);
    $period = $year.$month;
    $co_code = substr($quono,0,3);
    $sqlfind = "select sid, quono, qid, runningno, noposition  from $pro2103 "
            . "WHERE quono = '$quono' and qid = '$qid' and runningno = '$runningno' "
            . "AND noposition = '$noposition' ";
    
    $objsqlfind = new SQL($sqlfind);
    if($noposition < 10){
        $no = "0".strval($noposition);
    }elseif($noposition >= 10){
        $no = strval($noposition);
    }
        
    switch ($jlfor) {
        case "CJ";

            $bid = 1;
            break;
        case "SB";

            $bid = 2;
            break;
        default:
            $bid = "";
            break;
    }    
    if($runningno < 10){
        $runno = "000".strval($runningno);
    }elseif($runningno >= 10 and $runningno < 100){
        $runno = "00".strval($runningno);
    }elseif($runningno >= 100 and $runningno < 999){
        $runno = "0".strval($runningno);
    }elseif ($runningno >= 1000 ) {
        $runno = strval($runningno);
    }else{
        $runno = strval($runningno);
    }
    
    
    $jobcode = $jlfor." ".$co_code." ".$period." ".$runno." ".$no;
    echo "<br>$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$4<br>";
    echo "\$jobcode = $jobcode <br>";
    $period_date_issue = check_date_issue_period($date_issue);
    
    echo "\$period_date_issue =  $period_date_issue <br>";
    
    $getResultJobcode =  check_jobcodesid($jobcode); 
    echo "<p style='color:red;background-color:white;'>The check_jobcode function "
    . "detect jobcode $jobcode result in answer of $getResultJobcode <br>";
    #########################################################################
    $jobcodeResultArray = getJobcodeDetail($jobcode);
    echo "<p style='color:red;background-color:white;'> List down Jobcodedetails <br>";
    var_dump($jobcodeResultArray);
    echo " End of List down Jobcodedetails <br> </p>";
    
    ##########################################################################
    $checkIsExistInSche2104 = IsExistScheduling2104($qid, $quono,$noposition, $bid);
    //var_dump($checkIsExistInSche2104);

    if (!isset($checkIsExistInSche2104)){
        echo "<p style='color:orange;background-color:red;'>Not found any data in $pro2104 <br> The getResultJobcode = $getResultJobcode </p><br> ";
         echo "in Line 253<br>";
        if($getResultJobcode == 1){//if found data in jobcodsid
            $resultArray = getJobcodeDetail($jobcode);
            $jobcode_sid = $resultArray['sid'];
            $jocode_period = $resultArray['period'];
            if($jobcode_period != $period){
                //period in jobcode does not match period in $pro2104
                // get the $output2104 detial by jobcode_sid ,since it is not = period in 2103, it should be the pointer in $output2104
                $checkresult = IsExistOutput2104($jobcode_sid);
                if($checkresult != 'no result on getResultRowArray'){//mean found result in Output2104
                     
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

                       #########################################################
                       $sqlinsert1 = "INSERT INTO $output2103 (poid, sid, jobtype,"
                           . " date_start, start_by, machine_id, date_end, "
                           . "end_by, quantity, totalquantity,remainingquantity )  VALUES"
                           . "(NULL, $sid, '$jobtype', '$date_start', "
                           . "'$start_by', '$machine_id', '$date_end', '$end_by', "
                           . "$quantity,$totalquantity,"
                           . "$remainingquantity)";
                       echo "\$sqlinsert1 = $sqlinsert1 <br>";
                       //$insertResult = insertToOutput2102($Insert_Array);
                       $insertResult = insBySqlOutput2103($sqlinsert1);
                       echo "The insertionresult is $insertResult <br>";
                        $resultUpdate = updatejobcodesid($jobcode,$period, $sid);
                        $jcudpatecount++;
                        echo "<p style='color:orange;background-color:red;'>update the jobcodesid table for $joblistno with period = $period and sid = $sid"
                                . " the result is $resultUpdate <br> </p>";
                    

                }
                    unset($checkresult);
                    unset($poid);
                    unset($jobtype);
                    unset($date_start_str);
                    unset($date_start);
                    unset($start_by);
                    unset($machine_id);
                    unset($date_end_str);
                    unset($date_end);
                    unset($end_by);
                    unset($quantity);
                    unset($totalquantity);
                    unset($remainingquantity);
                    unset($sqlinsert1);
                    unset($insertResult);


                }
            }
        }
    }else{//found record in Sche2104
        echo "in else if Line 214 <br> ";
        if(empty($checkIsExistInSche2104)){
            echo "<p style='color:orange;background-color:red;'>Not found any data in $pro2104  <br> The getResultJobcode = $getResultJobcode</p><br> ";
            #######################################################################3
            echo "in Line 336 <br>";
                    if($getResultJobcode == 1){//if found data in jobcodsid
                        $resultArray = getJobcodeDetail($jobcode);
                        $jobcode_sid = $resultArray['sid'];
                        $jobcode_period = $resultArray['period'];
                        echo "\$jobcode_period = $jobcode_period ,  \$period =  $period<br>";
                        if($jobcode_period != $period){
                            //period in jobcode does not match period in $pro2104
                            // get the $output2104 detial by jobcode_sid ,since it is not = period in 2103, it should be the pointer in $output2104
                            $checkresult = IsExistOutput2104($jobcode_sid);
                            if($checkresult != 'no result on getResultRowArray'){//mean found result in Output2104

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
                                    echo "<p style='color:red;background-color:white;'>jobtype = $jobtype, date_start = $date_start, "
                                            . " start_by = $start_by, machine_id = $machine_id, "
                                            . "date_end = $date_end, end_by  = $end_by , "
                                            . "quantity = $quantity, totalquantity = $totalquantity,remainingquantity = $remainingquantity<br>";                

                                   #########################################################
                                   $sqlinsert1 = "INSERT INTO $output2103 (poid, sid, jobtype,"
                                       . " date_start, start_by, machine_id, date_end, "
                                       . "end_by, quantity, totalquantity,remainingquantity )  VALUES"
                                       . "(NULL, $sid, '$jobtype', '$date_start', "
                                       . "'$start_by', '$machine_id', '$date_end', '$end_by', "
                                       . "$quantity,$totalquantity,"
                                       . "$remainingquantity)";
                                   echo "\$sqlinsert1 = $sqlinsert1 <br>";
                                   //$insertResult = insertToOutput2102($Insert_Array);
                                   $insertResult = insBySqlOutput2103($sqlinsert1);
                                   echo "The insertionresult is $insertResult </p><br>";
                                   ###################################################
                                   echo "<p style='color:orange;background-color:red;'>After insert the data rows from $output2104 into $output2103, "
                                           . "have to remove the data rosw from $output2104 </p>";
                                    $delResultOutput2104 = deloutput2104($jobcode_sid);
                            
                                    if(isset($delResultOutput2104)){
                                        if($$delResultOutput2104 == "deleted"){
                                            echo "<p style='color:orange;background-color:red;'>The record in  table $output2104 for sid = $jobcode_sid have been removed <br>";
                                            $deloutputcount++;
                                            echo " The record that delete from $output2104  is no. deloutputcount <br> </p> ";
                                        }
                                    }
                                    unset($delResultSche2104);  
                                    #############################################3
                                    ## update the period and sid of 2103 in jobcodesid 
                                    
                                                                      

                                    $resultUpdate = updatejobcodesid($jobcode,$period, $sid);
                                    $jcudpatecount++;
                                    echo "<p style='color:orange;background-color:red;'>update the jobcodesid table for $joblistno with period = $period and sid = $sid"
                                            . " the result is $resultUpdate <br> </p>";

                            }
                                unset($checkresult);
                                unset($poid);
                                unset($jobtype);
                                unset($date_start_str);
                                unset($date_start);
                                unset($start_by);
                                unset($machine_id);
                                unset($date_end_str);
                                unset($date_end);
                                unset($end_by);
                                unset($quantity);
                                unset($totalquantity);
                                unset($remainingquantity);
                                unset($sqlinsert1);
                                unset($insertResult);


                            }else{//$checkresult == 'no result on getResultRowArray'no result of $output2104
                                //no result on getResultRowArray
                                //$jobcode_period != $period
                                //$output2014 no result
                                $resultUpdate = updatejobcodesid($jobcode,$period, $sid);
                                $jcudpatecount++;
                                echo "<p style='color:orange;background-color:red;'>update the jobcodesid table for $joblistno with period = $period and sid = $sid"
                                        . " the result is $resultUpdate <br> </p>";

                                
                            }
                        }
                    }

            
        }else{
//        var_dump($checkIsExistInSche2104);
            $sid_nextperiod = $checkIsExistInSche2104['sid'];
            $foundcount++;
            ## get the sid of data in 2104 scheduling
            //$sid_2 =getSidSche2104($qid, $quono,$noposition, $bid);
         echo "<p style='color:white;background-color:blue;'>Found data record in $pro2104 </p> <br> ";
         echo "</br><p style='color:white;background-color:green;'>var_dump the data </p> <br> ";
            var_dump($checkIsExistInSche2104);
         echo "</br><p style='color:white;background-color:green;'>end  of var_dump  data </p> <br> ";
         
        echo "<p style='color:white;background-color:red;'>This data should keep a copy in $pro2103 and delete the version in $pro2104  ,</p><br> ";
        #DELETE DATA IN $pro2104
        $delResultSche2104 = delSche2104($sid_nextperiod);
        if(isset($delResultSche2104)){
            if($delResultSche2104 == "deleted"){
                $delcount++;
            }
        }
        unset($delResultSche2104);
                    
        
       # echo "<p style='color:red;background-color:white;'>delete the data in $pro2104  ,</p><br> ";
        
        echo "<p style='color:white;background-color:red;'>Find out  in $output2104 have exist data ?  ,</p><br> ";
            $checkResult2104 =IsExistOutput2104($sid_nextperiod);
            if($checkResult2104 == 'no result on getResultRowArray'){
                echo "checkResult2104 is no result on getResultRowArray <br>"
                . "there are no result on function IsExistOutput2104 <br>";
                echo "<p style='color:red;background-color:white;'>no result data in $output2104  ,</p><br> ";
                $jobcode_array = getJobcodeDetail($jobcode);
                $jobcode_period = $jobcode_array['period'];
                $jobcode_sid =  $jobcode_array['sid'];
                echo "<p style='color:blue;background-color:white;'>jobcode_period =  $jobcode_period , period = $period  ,</p><br> ";
                if(strval($jobcode_period) == strval($period)){
                    #DELETE DATA IN $pro2104
                    echo "<p style='color:red;background-color:white;'>DELETE DATA IN $pro2104 ,</p><br> ";
                    $delResultSche2104 = delSche2104($sid_nextperiod);
                    echo "<p style='color:red;background-color:white;'>the result od deletion \$delResultSche2104 "
                    . " =   $delResultSche2104 </p><br>";
                    if(isset($delResultSche2104)){
                        if($delResultSche2104 == "deleted"){
                            $delcount++;
                        }
                    }
                    unset($delResultSche2104);
                    
                }

                if ($getResultJobcode == 0) {//not exist in jobcodesid yet
                    echo "<p style='color:red;background-color:white;'>$jobcode is not exist in jobcodesid yet  ,</p><br> ";
                    echo "<p style='color:red;background-color:white;'>delete the data in $pro2104  ,</p><br> ";
                    #DELETE DATA IN $pro2104
                    $delResultSche2104 = delSche2104($sid_nextperiod);
                    echo "<p style='color:red;background-color:white;'>the result od deletion \$delResultSche2104 "
                    . " =   $delResultSche2104 </p><br>";
                    if(isset($delResultSche2104)){
                        if($delResultSche2104 == "deleted"){
                            $delcount++;
                        }
                    }
                    unset($delResultSche2104);
        
                }

            }else{
                if($getResultJobcode == 1){
                    $jobcode_array = getJobcodeDetail($jobcode);
                    $jobcode_period = $jobcode_array['period'];
                    $jobcode_sid =  $jobcode_array['sid'];
                    
                    echo "<p style='color:red;background-color:white;'>$jobcode is exist in jobcodesid, the jobcode_sid is  $jobcode_sid "
                            . " , and the jobcode_period = $jobcode_period</p><br> ";
                    echo "<p style='color:red;background-color:white;'>checkResult2104 is having result on getResultRowArray<br>"
                    . "there are result on function IsExistOutput2104 </p><br>";

                    ## $output2104 have data
                    $checkResult2103=IsExistOutput2103($sid);
                    if($checkResult2103 == 'no result on getResultRowArray'){
                    echo "<p style='color:red;background-color:white;'> checkResult2103 is no result on getResultRowArray <br>"
                    . "there are no result on function IsExistOutput2103 </p><br>";
                    echo "<p style='color:white;background-color:blue;'>confirm the $output2104 have data and $output2103 "
                    . "do not have any data , if so, move the data to  $output2103 </p> <br>" ;

                    }else{// mean double records in output
                        echo "<p style='color:red;background-color:white;'>checkResult2103 is having result on getResultRowArray <br>"
                        . "there are  result on function IsExistOutput2103 ,mean double records in both $output2103 and $output2104 </p><br>";
                    }
                }
            }
        
        
        
        }
    }
    /*
                            $sqlinsert1 = "INSERT INTO $output2103 (poid, sid, jobtype,"
                                . " date_start, start_by, machine_id, date_end, "
                                . "end_by, quantity, totalquantity,remainingquantity )  VALUES"
                                . "(NULL, $sid, '$jobtype', '$date_start', "
                                . "'$start_by', '$machine_id', '$date_end', '$end_by', "
                                . "$quantity,$totalquantity,"
                                . "$remainingquantity)";
                        echo "\$sqlinsert1 = $sqlinsert1 <br>";
                        //$insertResult = insertToOutput2102($Insert_Array);
                        $insertResult = insBySqlOutput2103($sqlinsert1);
                        echo "The insertionresult is $insertResult <br>";
                        */
    
    
    #$found = $objsqlfind->getResultOneRowArray();
    
    /*if(empty($found)){
       echo "<br> not found match of jobcode $jobcode in jobcodesid <br>";
       $IsSche2104 = IsExistScheduling2104($sid);
       // if $
       
    }else{
        echo "====== AT $pro2103 ========<BR> ";
        echo "sid = $sid, quono =  $quono, runningno = $runningno , noposition = $noposition, jlfor - $jlfor <br> ";
        echo "======END $pro2103 ========<BR> ";
        echo "there is result in $pro2103 <br>";
        echo " \$sqlfind =  $sqlfind<br>";
        $sid2 = $found['sid'];
//        $quono2 = $objsqlfind['quono'];
        echo "<br>################################################################################################<br>";
        echo "date_issue = $date_issue ,year = $year, month = $month , period = $period , co_code = $co_code<br>";
        echo "sid2 in $pro2104 is $sid2 <br>";
        echo "<br> jobcode = $jobcode<br>";
        $sqlJc = "select * from jobcodesid where jobcode = '$jobcode'";
        $sqlJc = new SQL($sqlJc);
        $joblistno = $sqlJc->getResultOneRowArray();
        if(empty($joblistno)){
            
            echo "no jobcode found in table jobcodesid, the joblist not yet being scanned<br>";
            //check production_output_period
            $checkResult2103 = IsExistOutput2103($sid);
//            $sqloutput2103 = "select * from $output2103 where sid = '$sid'";
//            $objoutput1 = new SQL($sqloutput2103);
//            $resultoutput1 = $objoutput1->getResultRowArray(); 
            $resultoutput1 = $checkResult2103 ;
            echo "<br>------------$output2103-------------------------<br>";
            if(empty($resultoutput1)){
                echo "no result on $output2103 <br>";
                
            }else{
                 echo "there are result on $output2103 <br>";
                 var_dump($resultoutput1);
                 echo"<br>";
            }
            echo "<br>------------$output2104--------------------------------<br>";
            $checkResult2104 =IsExistOutput2104($sid2);
            if($checkResult2104 == 'no result on getResultRowArray'){
                echo "checkResult2104 is no result on getResultRowArray <br>"
                . "there are no result on function IsExistOutput2104 <br>";
            }else{
                echo "checkResult2104 is having result on getResultRowArray <br>"
                . "there are  result on function IsExistOutput2104 <br>";
            }
//            $sqloutput2104 = "select * from $output2104 where sid = '$sid2'";
//            $objoutput2 = new SQL($sqloutput2104);
//            $resultoutput2 = $objoutput2->getResultRowArray();
            $resultoutput2 = $checkResult2104;
            if($resultoutput2 != 'no result on getResultRowArray'){
//                echo "\$resultoutput2 = $resultoutput2 <br>";
//                print_r($resultoutput2);
//                echo "<br>";
                 echo " Line 192, resultoutput2 is not no result on getResultRowArray <br>"
                . "there are result on $sqloutput2104<br>";
                 var_dump($resultoutput3);
                 echo"<br>";
            }else{
                echo "Line 197 resultoutput3 is no result on getResultRowArray <br>"
                . "there are no result on $output2104<br>";
                // echo "\$resultoutput3 = $resultoutput3 <br>";
                // echo "\$sqloutput2104 = $sqloutput2104  <br>";
                // echo "no result on $sqloutput2104 <br>";
                $delResultSche2104 = delSche2104($sid2);
                
                $delcount++;
                if($delResultSche2104 == 'deleted'){
                    echo "$pro2104 where sid = $sid2 had been  $delResultSche2104 <br>";
                }else{
                    echo "$pro2104 where sid = $sid2 had been  $delResultSche2104 <br>";
                }
                unset($delResultSche2104);
                
                
            }
            
            if(!empty($resultoutput1) && (empty($resultoutput2))){
                echo "1st rectification of table jobcodesid  found emtpy, and $sqloutput2104 found  empty <br>";
                echo "$sqloutput2103 have result, and $sqloutput2104 is return empty<br>";
                echo "remove the record where sid = $sid2 in $pro2104 <br> ";
                $sqldel = "DELETE FROM $pro2104 where sid = $sid2";
                echo "\$sqldel = $sqldel <br>";
                $delcount++;
                $objdele = new SQL($sqldel);
                $resultdel = $objdele->getDelete();
                echo "$resultdel <br>";
                unset($objdele);
                unset($resultdel);
            } elseif (empty($resultoutput1) && empty($resultoutput2)) {
                
                echo "2nd rectification of table jobcodesid  found emtpy,<br>"
                . " and $sqloutput2104 found  empty  with $sqloutput2103 also found empty<br>";
                $sqldel = "DELETE FROM $pro2104 where sid = $sid2";
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
                $checkresult = IsExistOutput2104($sid2);
                if($checkresult == 'no result on getResultRowArray'){
                    echo "Line 257,  \$checkresult = $checkresult <br>";
                    $delResultSche2104 = delSche2104($sid2);
                    $delcount++;
                    echo "\$delResultSche2104 = $delResultSche2104 <br>";
                    unset($delResultSche2104);
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
                            
                        );
                        $sqlinsert1 = "INSERT INTO $output2103 (poid, sid, jobtype,"
                                . " date_start, start_by, machine_id, date_end, "
                                . "end_by, quantity, totalquantity,remainingquantity )  VALUES"
                                . "(NULL, $sid, '$jobtype', '$date_start', "
                                . "'$start_by', '$machine_id', '$date_end', '$end_by', "
                                . "$quantity,$totalquantity,"
                                . "$remainingquantity)";
                        echo "\$sqlinsert1 = $sqlinsert1 <br>";
                        //$insertResult = insertToOutput2102($Insert_Array);
                        $insertResult = insBySqlOutput2103($sqlinsert1);
                        echo "The insertionresult is $insertResult <br>";
                        
                        //delete from production_output_2103 where sid = $sid2
                        $delResultOutput2103 = deloutput2104($sid2);
                        echo "Line 341 \$delResultOutput2104 = $delResultOutput2104 <br>";
                        unset($delResultSche2104);
                        //delere from production_scheduling_2103 where sid = $sid2;
                        $delResultSche2104 = delSche2104($sid2);
                        echo "Line 345 \$delResultSche2104 = $delResultSche2104 <br>";
                        unset($delResultSche2104);
                        
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
                //if found some things in output2104 then isert into output2103
                $checkResult2104 =IsExistOutput2104($sid2);
                echo "Line 366 <br>";
                if($checkResult2104 == 'no result on getResultRowArray'){
                    echo "checkResult2104 is no result on getResultRowArray <br>"
                    . "there are no result on function IsExistOutput2104 <br>";
                }else{
                    echo "checkResult2104 is having result on getResultRowArray <br>"
                    . "there are  result on function IsExistOutput2104 <br>";
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
    unset($objsqlfind);*/
    
    
}
unset($result1);
echo "total found issue records is $foundcount <br>";
echo "total delete record is $delcount <br>";
echo "total count record is $totalcount <br>";
echo "total jobcodesid update count is $jcudpatecount <br>";