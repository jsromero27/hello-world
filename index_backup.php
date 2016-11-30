<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GENELOGY</title>
<script type="text/javascript" src="boxover.js"></script>
<style type="text/css">
.mainTable
{
	border:#666666 1px solid;	
}
.mainTable td
{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:.9em;
	text-align:center;
	cursor:default;
}
.internalTable td
{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:.9em;
	border:#666666 1px dotted;
	text-align:center;
	cursor:default;
}
.toolHeader 
{
	background-color: #D3E4A6;
	font-family:Arial,Tahoma;
	font-weight: bold;
	font-size:12px;
	color: Black;
	padding: 3px;
	border: solid 2px #9CC525;
}
.toolBody
{
	background:#FFFFFF;
    font-family:Arial,Tahoma;
    font-size:12px;
    padding:5px;
    border: solid 2px #9CC525;
    border-top:none;
    color: Blue;
    /* color: #F4921B; */
}
</style>
</head>
<form action="index.php" method="post">
<p>Login ID: <input type="text" name="yourname" /><br />
</p>
<p><input type="submit" value="Login"></p>
</form>
<br />
<?php
/*
<body>
<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
<input type="submit" name="submit" value="Request Payout and View History">
</form>
*/
$member=$_POST['yourname'];
//echo $member;
// Connecting, selecting database
$link = mysql_connect('localhost', 'root', '') or die('Could not connect: ' . mysql_error());
//echo 'Connected successfully';
mysql_select_db('system_db') or die('Could not select database');
/*
// Performing SQL query
$query = 'SELECT * FROM user_info';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());
// Printing results in HTML
echo "<table border='1'>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
{
    echo "\t<tr>\n";
    foreach ($line as $col_value) 
	{
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";
// Free resultsets
//mysql_free_result($result);
*/
//echo getDownline("081118361");
//echo "N<br />";
$nextDay = 12;
$nextDate = (date('d') < $nextDay) ? date('Y-m', strtotime('+1 month')).'-'.$nextDay : date('Y-m').'-'.$nextDay;
$dat = date('Y-m-d');
if ($dat>=$nextDate)
  	{
  	GenerateTree($member);
  	}
else
	{
	echo "Records are released on the 12th day of every month."."<br /><br /> Please try again on  or after the 12th";
	}
function GenerateTree($memberid)
{
	//global $memberid;
	$query = "SELECT * FROM user_info where s_id='".$memberid."' order by form_type";
	//echo $query;
	$result = mysql_query($query);
	//var_dump($result);	
	while($rowe = mysql_fetch_array($result))
	{
	$loge = $rowe['login_id'];
	//echo $loge . " ";
	}
	mysql_free_result($result);		
	//echo "<table border='0' class='mainTable' cellspacing='0' cellpadding='3' style='width:100%'>\n";
	echo "<tr><td colspan='3' style='text-align:center;'> ".getInfo($memberid)."<br />"."</td></tr>";
	echo "<tr><td colspan='3'>".getDownline($memberid)."</td></tr>";
	//echo "<tr><td style='width:33%;'>".$l1."</td><td style='width:33%;'>".$l2."</td><td style='width:34%;'>".$l3."</td></tr>";
	echo "</table>\n";
}
function getDownline($memberid)
{
	global $num_rows_total;
	global $money;
	$query = "SELECT login_id FROM user_info where s_id='".$memberid."'order by form_type";
	$result = mysql_query($query);
	$array = array();
	$num_rows = mysql_num_rows($result);
	$array[]=$num_rows;
	//echo $num_rows;
	$num_rows_total = $num_rows_total+$num_rows;
	$money =$money+$num_rows*.25;
	while($row = mysql_fetch_array($result))
	{
	$log1 = $row['login_id'];
	echo"Level 1 ($0.25) "." - > ".$log1 . "<br /> ";
	getFurther1($log1);
	}
}
//echo $num_rows_total;
function getFurther1($log1)
{	
	global $num_rows_total;
	global $money;
	$querys = "SELECT login_id FROM user_info where s_id='".$log1."' order by form_type";
	$results = mysql_query($querys);
	$array1 = array();
	$num_rows1 = mysql_num_rows($results);
	$array1[]=$num_rows1;
	//echo $num_rows1;
	$num_rows_total = $num_rows_total+$num_rows1;
	$money =$money + $num_rows1*.50;
	while($rows = mysql_fetch_array($results))
	{	
	$log2 = $rows['login_id'];
	echo"Level 2 ($0.50) "." - - > ".$log2 . "<br /> ";
	getFurther2($log2);
	}
}
//echo $num_rows_total;
function getFurther2($log2)
{
 	global $num_rows_total;
	global $money;
	$queryss = 	"SELECT login_id FROM user_info where s_id='".$log2."' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$num_rows2 = mysql_num_rows($resultss);
	//echo $num_rows2;
	$array = array();
	$array[]=$num_rows2;
	$num_rows_total = $num_rows_total+$num_rows2;
	$money =$money+$num_rows2*.75;
	while($rowss = mysql_fetch_array($resultss))
	{
	$log3 = $rowss['login_id'] . " ";
	echo"Level 3 ($0.75) "." - - - > ".$log3."<br /> ";
	getFurther3($log3);
	}
}
function getFurther3($log3)
{	
	global $num_rows_total;
	global $money;
	$queryss = 	"SELECT login_id FROM user_info where s_id='".$log3."' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$num_rows3 = mysql_num_rows($resultss);
	//echo $num_rows3;
	$array = array();
	$array[]=$num_rows3;
	$num_rows_total = $num_rows_total+$num_rows3;
	$money =$money+$num_rows3*1.00;
	while($rowss = mysql_fetch_array($resultss))
	{
	$log4 = $rowss['login_id'] . " ";
	echo"Level 4 ($1.00) "." - - - - > ".$log4."<br /> ";
	getFurther4($log4);
	}
}	
function getFurther4($log4)
{
	global $num_rows_total;	
	global $money;
	$queryss = 	"SELECT login_id FROM user_info where s_id='".$log4."' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$num_rows4 = mysql_num_rows($resultss);
	//echo $num_rows4;
	$array = array();
	$array[]=$num_rows4;
	$num_rows_total = $num_rows_total+$num_rows4;
	$money =$money+$num_rows4*1.25;
	while($rowss = mysql_fetch_array($resultss))
	{
	$log5 = $rowss['login_id'] . " ";
	echo"Level 5 ($1.25) "." - - - - - > ".$log5."<br /> ";
	getFurther5($log5);
	}
}	
function getFurther5($log5)
{
 	global $num_rows_total;
	global $money;	
	$queryss = 	"SELECT login_id FROM user_info where s_id='".$log5."' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$num_rows5 = mysql_num_rows($resultss);
	//echo $num_rows5;
	$array = array();
	$array[]=$num_rows5;
	$num_rows_total = $num_rows_total+$num_rows5;
	$money =$money+$num_rows5*1.50;
	while($rowss = mysql_fetch_array($resultss))
	{
	$log6 = $rowss['login_id'] . " ";
	echo"Level 6 ($1.50) "." - - - - - - > ".$log6."<br /> ";
	getFurther6($log6);
	}
}	
function getFurther6($log6)
{
	global $num_rows_total;	
	global $money;
	$queryss = 	"SELECT login_id FROM user_info where s_id='".$log6."' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$num_rows6 = mysql_num_rows($resultss);
	//echo $num_rows6;
	$array = array();
	$array[]=$num_rows6;
	$num_rows_total = $num_rows_total+$num_rows6;
	$money =$money+$num_rows6*1.75;
	while($rowss = mysql_fetch_array($resultss))
	{
	$log7 = $rowss['login_id'] . " ";
	echo"Level 7 ($1.75) "." - - - - - - - > ".$log7."<br /> ";
	getFurther7($log7);
	}
}	
function getFurther7($log7)
{
	global $num_rows_total;	
	global $money;
	$queryss = 	"SELECT login_id FROM user_info where s_id='".$log7."' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$num_rows7 = mysql_num_rows($resultss);
	//echo $num_rows7;
	$array = array();
	$array[]=$num_rows7;
	$num_rows_total = $num_rows_total+$num_rows7;
	$money =$money+$num_rows7*2.00;
	while($rowss = mysql_fetch_array($resultss))
	{
	$log8 = $rowss['login_id'] . " ";
	echo"Level 8 ($2.00) "." - - - - - - - - > ".$log8."<br /> ";
	}	
}
if(isset($_POST['yourname']))
{
	if($dat>=$nextDate)	
	{	
	echo "<br /><br /> Downlines (up to 8 levels below) this month =  ". $num_rows_total;
	echo "<br />Total credit due to your account this month  =  $".$money."<br /><br />"; 
	echo "Uplines:"."<br />*************************<br />";
	foo($money,$member);
	}
}
function getUpline1($member)
{
	$query = "SELECT s_id FROM user_info where login_id='".$member."'order by form_type";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	{
	$log1 = $row['s_id'];
	echo"Level 1 ($0.25) "." - > ".$log1 . "<br /> ";
	getUpline2($log1);
	}
}
function getUpline2($log1)
{
	$query2 = "SELECT s_id FROM user_info where login_id='".$log1."'order by form_type";
	$result2 = mysql_query($query2);
	while($row2 = mysql_fetch_array($result2))
	{
	$log2 = $row2['s_id'];
	echo"Level 2 ($0.50) "." - > ".$log2 . "<br /> ";
	getUpline3($log2);
	}
}
function getUpline3($log2)
{
	$query3 = "SELECT s_id FROM user_info where login_id='".$log2."'order by form_type";
	$result3 = mysql_query($query3);
	while($row3 = mysql_fetch_array($result3))
	{
	$log3 = $row3['s_id'];
	echo"Level 3 ($0.75) "." - > ".$log3 . "<br /> ";
	getUpline4($log3);
	}
}
function getUpline4($log3)
{
	$query4 = "SELECT s_id FROM user_info where login_id='".$log3."'order by form_type";
	$result4 = mysql_query($query4);
	while($row4 = mysql_fetch_array($result4))
	{
	$log4 = $row4['s_id'];
	echo"Level 4 ($1.00) "." - > ".$log4 . "<br /> ";
	getUpline5($log4);
	}
}
function getUpline5($log4)
{
	$query5 = "SELECT s_id FROM user_info where login_id='".$log4."'order by form_type";
	$result5 = mysql_query($query5);
	while($row5 = mysql_fetch_array($result5))
	{
	$log5 = $row5['s_id'];
	echo"Level 5 ($1.25) "." - > ".$log5 . "<br /> ";
	getUpline6($log5);
	}
}
function getUpline6($log5)
{
	$query6 = "SELECT s_id FROM user_info where login_id='".$log5."'order by form_type";
	$result6 = mysql_query($query6);
	while($row6 = mysql_fetch_array($result6))
	{
	$log6 = $row6['s_id'];
	echo"Level 6 ($1.50) "." - > ".$log6 . "<br /> ";
	getUpline7($log6);
	}
}
function getUpline7($log6)
{
	$query7 = "SELECT s_id FROM user_info where login_id='".$log6."'order by form_type";
	$result7 = mysql_query($query7);
	while($row7 = mysql_fetch_array($result7))
	{
	$log7 = $row7['s_id'];
	echo"Level 7 ($1.75) "." - > ".$log7 . "<br /> ";
	getUpline8($log7);
	}
}
function getUpline8($log7)
{
	$query8 = "SELECT s_id FROM user_info where login_id='".$log7."'order by form_type";
	$result8 = mysql_query($query8);
	while($row8 = mysql_fetch_array($result8))
	{
	$log8 = $row8['s_id'];
	echo"Level 8 ($2.00) "." - > ".$log8 . "<br /> ";
	//getUpline9($member);
	}
}
/*
if(isset($_POST['submit']))
{
	global $money;
	foo($money,$member);
}
*/
function foo($money,$member)
{
	$query = "UPDATE user_info SET phone_office='$money' WHERE login_id='$member'";
	mysql_query($query);
	$daa=date(' D, d M Y g:i:s a');
	$queryss = 	"SELECT ifsc_code FROM user_info where login_id='$member' order by form_type";
	//echo$queryss;
	$resultss = mysql_query($queryss);
	$rowss = mysql_fetch_array($resultss);
	$log8 = $rowss['ifsc_code'] . " ";
	$query2 = "UPDATE user_info SET ifsc_code='|| ($money) - > $daa || $log8' WHERE login_id='$member'";
	mysql_query($query2);
	getUpline1($member);
	//recover($member);
	//echo$money;
}
	function recover($member)
{
	global $money;
	$query3 = "UPDATE user_info SET phone_office='$money' WHERE login_id='$member'";
	mysql_query($query3);
	$queryssc = 	"SELECT ifsc_code FROM user_info where login_id='$member' order by form_type";
	//echo$queryss;
	$resultssc = mysql_query($queryssc);
	$rowssc = mysql_fetch_array($resultssc);
	$log8c = $rowssc['ifsc_code'] . " ";
	$daaa=date(' D, d M Y g:i:s a');
	$query2c = "UPDATE user_info SET ifsc_code='<<< <strong>($money)</strong> $daaa >>> $log8c' WHERE login_id='$member'";
	global $money;
	$money=0;
	mysql_query($query2c);
	$querysscc = 	"SELECT ifsc_code FROM user_info where login_id='$member' order by form_type";
	//echo$queryss;
	$resultsscc = mysql_query($querysscc);
	$rowsscc = mysql_fetch_array($resultsscc);
	$log8cc = $rowsscc['ifsc_code'] . " ";
	echo"<br />".$log8cc;
	mysql_close(); 	
}
function getInfo($id)
{
	$query = "SELECT * FROM user_info where login_id='".$id."'";
	$result = mysql_query($query);
	$res;
	while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
	{
    $res = "<br />Login ID: ".$row["login_id"]."<br />Name: ".$row["m_name"]."<br /><br />Downlines:"."<br />*************************";
	//."<br />Application No: ".$row["app_no"]."<br />"
	}
	mysql_free_result($result);
	return $res;
}
function getCount($id)
{
	$query = "SELECT * FROM user_info where s_id='".$id."'";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	echo$count;
	$_SESSION['s'] = $_SESSION['s']+$count;
	while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
	{
    getCount($row["login_id"]);
	}
	mysql_free_result($result);
	echo $count."";
	//return "";	
}
// Closing connection
mysql_close($link);
?>
</body>
</html>