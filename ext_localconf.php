<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Calculate delivery cost
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/pi2/class.tx_commerce_pi2.php']['generateBasket'] = 'EXT:grb_commerce_cost/Classes/Controller/CommerceDeliveryCostController.php:Tx_GrbCommerceCost_Controller_CommerceDeliveryCostController';
?>