<?php

include "./class/dbh.inc.php";
include "./class/variables.inc.php";
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
$delcount = 0;
foreach ($result1 as $array){
    
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
        

    
    $jobcode = $jlfor." ".$co_code." ".$period." ".$runningno." ".$no;
    $found = $objsqlfind->getResultOneRowArray();

    if(empty($found)){
//        echo "<br> not found match of $quono, with qid = $qid , runningno = $runningno <br>";
    }else{
        echo "====== AT $pro2102 ========<BR> ";
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
            $sqloutput2102 = "select * from $output2102 where sid = '$sid'";
            $objoutput1 = new SQL($sqloutput2102);
            $resultoutput1 = $objoutput1->getResultRowArray();
            echo "<br>------------$output2102-------------------------<br>";
            if(empty($resultoutput1)){
                echo "no result on $sqloutput2102 <br>";
                
            }else{
                 echo "there are result on $sqloutput2102 <br>";
                 var_dump($resultoutput1);
                 echo"<br>";
            }
            echo "<br>------------$output2103--------------------------------<br>";
            $sqloutput2103 = "select * from $output2103 where sid = '$sid2'";
            $objoutput2 = new SQL($sqloutput2103);
            $resultoutput2 = $objoutput2->getResultRowArray();  
            if(empty($resultoutput2)){
                echo "no result on $sqloutput2103 <br>";
                
            }else{
                 echo "there are result on $sqloutput2103<br>";
                 var_dump($resultoutput2);
                 echo"<br>";
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
            
            echo "the record in table of $period , mean in production_scheduling_$period , "
                    . "the period store in jobcodesid is $periodsid <br>";
            $LOGICAL =strcmp($periodsid,$period);// = 0 IF SAME INDENTICAL
            if ($LOGICAL == 0){
                echo "the period ($period) vs periodsid ($periodsid)  is identical<br>";
            }else{
                echo "the period ($period) vs periodsid ($periodsid)  is not identical<br>";
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
    
    
}
unset($result1);
echo "total delete record is $delcount <br>";