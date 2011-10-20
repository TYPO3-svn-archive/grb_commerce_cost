<?php

########################################################################
# Extension Manager/Repository config file for ext "grb_commerce_cost".
#
# Auto generated 22-09-2011 09:39
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Shipping costs depend on weight',
	'description' => '',
	'category' => 'misc',
	'author' => 'Juerg Langhard',
	'author_email' => 'langhard@greenbanana.ch',
	'author_company' => 'GreenBanana GmbH',
	'shy' => '',
	'dependencies' => 'cms,commerce',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.0.4',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'commerce' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:9:{s:12:"ext_icon.gif";s:4:"e55b";s:17:"ext_localconf.php";s:4:"7018";s:14:"ext_tables.php";s:4:"dad2";s:14:"ext_tables.sql";s:4:"27fd";s:53:"Classes/Controller/CommerceDeliveryCostController.php";s:4:"3388";s:34:"Configuration/TypoScript/setup.txt";s:4:"36ae";s:40:"Resources/Private/Language/locallang.xml";s:4:"7cc1";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"3365";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";}',
);

?>