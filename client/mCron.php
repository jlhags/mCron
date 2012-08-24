#!/usr/bin/php
<?php
require 'includes/ConfigHandler.class.php';
$Conf = new ConfigHandler("conf.ini");
$connected = true;
$jobid = $argv[1];
$dsn = 'mysql:dbname=' . $Conf->GetValue("database","database") . ';host=' . $Conf->GetValue("database","host");
$user = $Conf->GetValue("database","user");
$password = $Conf->GetValue("database","password");

$server = $Conf->GetValue("client","name");
$description = $Conf->GetValue("client","description");
$id = -1;
try 
{
	$dbh = new PDO($dsn, $user, $password);
} 
catch (PDOException $e) 
{
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
#determine if registered with server
$sql = "SELECT id FROM servers WHERE name = $name";
$s = $sth->query($sql);
if(!$s)
{
	#Please allow me to introduce myself
	
	$sql = "INSERT INTO servers (name,description) VALUES (':name',':decription')";
	$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	if(!$sth->execute(array(':name' => $server, ':description' => $description)))
	{
		exit;
	}
	$id = $dbh->lastInsertId();
}
else
{
	$id = $s['id'];
}


$SQL = "SELECT * FROM jobs WHERE server_id = $id AND processed = false";

$crontab = '# This file was auto generated using mCron. DO NOT MODIFY THIS FILE. YOUR CHANGES WILL BE REMOVED\n\n';
$crontab .= '# Run the mCron client to check for update\n';
$crontab .= "*/5	*	*	*	*	" . dirname(__FILE__) . $argv[0]."\n";
$wrapper = dirname(__FILE__) . "wrapper.php" ;
$updated = false; 
foreach($sth->query($sql) as $job)
{
	$updated = true;
	$crontab .= '# ' . $job['decription'] . "\n";
	$crontab .= sprintf("%s	%s	%s	%s	%s	%s %s %s", $job['min'],$job['hour'],$job['min'],$job['dom'],$job['dow'],$wrapper,$job['id'],$job['command']);
}
if($updated)
{
	#write to crontab
}

?>