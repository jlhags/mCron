#!/usr/bin/php
<?php
require 'includes/ConfigHandler.class.php';
$Conf = new ConfigHandler("conf.ini");
$connected = true;
$jobid = $argv[1];
$dsn = 'mysql:dbname=' . $Conf->GetValue("database","database") . ';host=' . $Conf->GetValue("database","host");
$user = $Conf->GetValue("database","user");
$password = $Conf->GetValue("database","password");

try 
{
	$dbh = new PDO($dsn, $user, $password);
} 
catch (PDOException $e) 
{
    echo 'Connection failed: ' . $e->getMessage();
    $connected = false;
}
#get starting time
$start = date("Y-m-d G:i:s");

if($connected)
{
	$sql = "INSERT INTO logs (job_id,start_time) VALUES (':job_id',':starttime')";
	$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':job_id' => $jobid, ':starttime' => $start));
}

$logid = $dbh->lastInsertId();

array_shift($argv);
array_shift($argv);

exec(implode(" ",$argv) . " 2>&1" ,$output,$ret);
$end = date("Y-m-d G:i:s");
$success = ($ret ===0);
if($connected)
{
	$sql = "UPDATE logs SET end_time=:endtime,output=:output,success:success WHERE job_id=job_id";
	$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$sth->execute(array(':job_id' => $jobid, ':endtime' => $end,'output'=>implode("\n",$output),'succeeded'=> $success));
}

?>