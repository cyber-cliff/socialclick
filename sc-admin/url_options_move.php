<?php 
require('../config.php');
$mysqli = new mysqli();
$mysqli->connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
if($mysqli->connect_error) die("<h3>Cannot Connect Database Budu!! (".$mysqli->connect_errno.")</h3>");

if(!$mysqli->query('CREATE TABLE IF NOT EXISTS `url_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_id_fk` int(11) NOT NULL,
  `only_unique_visit` tinyint(1) NOT NULL DEFAULT \'0\',
  `hide_referrer` tinyint(1) NOT NULL DEFAULT \'0\',
  `except_country` varchar(255) NULL,
  `con_redirect_url` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;')) die($mysqli->error);

if($result = $mysqli->query("SELECT id,options FROM url")){
	while($row=$result->fetch_row()){
		$url_id = $row[0];
		$options = unserialize($row[1]);
		$only_unique = $options['ouv'];
		$hide_referrer = $options['hf'];
		$sql_insert = "	INSERT INTO 
						`url_options`(`id`, `url_id_fk`, `only_unique_visit`, `hide_referrer`, `except_country`, `con_redirect_url`)
						 VALUES (NULL,$url_id, '$only_unique', '$hide_referrer',NULL,NULL)";
		if(!$mysqli->query($sql_insert)){
			die($mysqli->error."| row ".$row[0]);
		}
	}
	echo "UPDATE DATABASE SUCCESSFULL";
}
?>