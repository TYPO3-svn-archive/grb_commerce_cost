<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Juerg Langhard <langhard@greenbanana.ch>, GreenBanana GmbH - www.greenbanana.ch
*  
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Controller for Delivery-Costs
 */
class Tx_GrbCommerceCost_Controller_CommerceDeliveryCostController{
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $parent
	 * @param unknown_type $basket
	 * @param unknown_type $deliveryContent
	 */
	function makeDelivery(&$parent, &$basket, &$deliveryContent){
		$parent->delProd = t3lib_div::makeInstance('tx_commerce_product');
		$parent->delProd->init($parent->conf['delProdId'], $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid']);
		$parent->delProd->load_data();
		$parent->delProd->load_articles();
		
		// Get the Article UID of the curent basket and create the sql query
		$queryWhere = '';
		foreach ($basket->basket_items as $item){
			if($item->getArticleTypeUid() === $parent->conf['regularArticleTypes']){
				$queryWhere .= 'uid='.$item->get_article_uid().' OR ';
			}
		}
		$queryWhere .= ' FALSE';
		
		// Get the weight and box of the articles
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, tx_grbcommercecost_weight, tx_grbcommercecost_box', 'tx_commerce_articles', $queryWhere);
		
		// Get the total weight and the box-type
		$totalWeight = '';
		$box = 'letter';
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
    		// 0 = letter
    		// 1 = paket
    		if($row['tx_grbcommercecost_box'] == 1){
    			$box = 'paket';
    		}
    		$totalWeight += $row['tx_grbcommercecost_weight']*$basket->getQuantity($row['uid']);
		}
		
		$boxOptions['letter'] = array_combine(t3lib_div::trimExplode(',', $parent->conf['delivery.']['box.']['letter.']['uid']),t3lib_div::trimExplode(',', $parent->conf['delivery.']['box.']['letter.']['maxWeight']));
		$boxOptions['paket'] = array_combine(t3lib_div::trimExplode(',', $parent->conf['delivery.']['box.']['paket.']['uid']),t3lib_div::trimExplode(',', $parent->conf['delivery.']['box.']['paket.']['maxWeight']));
		
		// Evaluate nedet box
		$usedBoxType = false;
		if($box == 'letter'){
			foreach ($boxOptions['letter'] as $deliveryUid=>$letterMaxWeight){
				if($totalWeight <= $letterMaxWeight){
					$usedBoxType = $deliveryUid;
					break;
				}
			}
		}
		
		if($box == 'paket' || $usedBoxType == false){
			foreach ($boxOptions['paket'] as $deliveryUid=>$letterMaxWeight){
				if($totalWeight <= $letterMaxWeight){
					$usedBoxType = $deliveryUid;
					break;
				}
			}
		}
		
		/*
		t3lib_div::debug($totalWeight);
		t3lib_div::debug($box);	
		t3lib_div::debug($boxOptions);
		t3lib_div::debug($usedBoxType);		
		*/
		
		
		// Delete all unused delivery-types
		foreach($parent->delProd->articles as $articleUid => $articleObj) {
			if($articleUid != $usedBoxType) {
				unset($parent->delProd->articles[$articleUid]);
				$basket->delete_article($articleUid);
			}
		}
		
	    // </customCode>			
		
		$parent->basketDel = $basket->get_articles_by_article_type_uid_asuidlist(DELIVERYArticleType);
		$select = '<input type="hidden" name="' . $parent->prefixId . '[delArt]" onChange="this.form.submit();">';

		if ($parent->conf['delivery.']['allowedArticles']) {
			$allowedArticles = explode(',', $parent->conf['delivery.']['allowedArticles']);
		}

		// Hook to define/overwrite individually, which delivery articles are allowed
		$hookObjectsArr = array();
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/pi2/class.tx_commerce_pi2.php']['deliveryArticles'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['commerce/pi2/class.tx_commerce_pi2.php']['deliveryArticles'] as $classRef) {
				$hookObjectsArr[] = &t3lib_div::getUserObj($classRef);
			}
		}
		foreach($hookObjectsArr as $hookObj) {
			if (method_exists($hookObj, 'deliveryAllowedArticles')) {
				$allowedArticles = $hookObj->deliveryAllowedArticles($parent, $allowedArticles);
			}
		}

		foreach($parent->delProd->articles as $articleUid => $articleObj) {
			if ((!is_array($allowedArticles)) || in_array($articleUid, $allowedArticles)) {
				if ($articleUid == $parent->basketDel[0]) {
					$first = 1;
					$price_net = tx_moneylib::format($articleObj->get_price_net(), $parent->currency);
					$price_gross = tx_moneylib::format($articleObj->get_price_gross(), $parent->currency);
				} elseif (!$first) {
					$price_net = tx_moneylib::format($articleObj->get_price_net(), $parent->currency);
					$price_gross = tx_moneylib::format($articleObj->get_price_gross(), $parent->currency);
					if (!is_array($parent->basketDel) || count($parent->basketDel) < 1) {
						$basket->add_article($articleUid);
						$basket->store_data();
					}
					$first = 1;
				}
			}
		}   		

		$basketArray['###DELIVERY_SELECT_BOX###'] = $select;
		$basketArray['###DELIVERY_PRICE_GROSS###'] = $price_gross;
		$basketArray['###DELIVERY_PRICE_NET###'] = $price_net;

 		$deliveryContent = $parent->cObj->substituteMarkerArrayCached($deliveryContent, $basketArray);
    	return $deliveryContent;
	}
	
	
}