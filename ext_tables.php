<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}


$tempColumns = array (
    'tx_grbcommercecost_weight' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:grb_commerce_cost/Resources/Private/Language/locallang_db.xml:tx_commerce_articles.tx_grbcommercecost_weight',        
        'config' => array (
            'type' => 'input',    
            'size' => '30',    
        )
    ),
    'tx_grbcommercecost_box' => array (        
        'exclude' => 0,        
        'label' => 'LLL:EXT:grb_commerce_cost/Resources/Private/Language/locallang_db.xml:tx_commerce_articles.tx_grbcommercecost_box',        
        'config' => array (
            'type' => 'radio',
            'items' => array (
                array('LLL:EXT:grb_commerce_cost/Resources/Private/Language/locallang_db.xml:tx_commerce_articles.tx_grbcommercecost_box.I.0', '0'),
                array('LLL:EXT:grb_commerce_cost/Resources/Private/Language/locallang_db.xml:tx_commerce_articles.tx_grbcommercecost_box.I.1', '1'),
            ),
        )
    ),
);

t3lib_div::loadTCA('tx_commerce_articles');
t3lib_extMgm::addTCAcolumns('tx_commerce_articles',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_commerce_articles','tx_grbcommercecost_weight;;;;1-1-1, tx_grbcommercecost_box');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'COMMERCE - Different delivery costs');
?>