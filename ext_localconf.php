<?php
if (!defined('TYPO3')) {
    die ('Access denied.');
}

call_user_func(static function (): void {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['registeredDrivers']['bynder2'] = [
        'class' => \JWeiland\Bynder2\Driver\BynderDriver::class,
        'shortName' => 'Bynder',
        'label' => 'Bynder',
        'flexFormDS' => 'FILE:EXT:bynder2/Configuration/FlexForms/Bynder.xml',
    ];

    // Show bynder authentication status in file storage
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1653038580] = [
        'nodeName' => 'bynderStatus',
        'priority' => '70',
        'class' => \JWeiland\Bynder2\Form\Element\BynderStatusElement::class,
    ];

    // Create cache for file information
    $extConf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \JWeiland\Bynder2\Configuration\ExtConf::class
    );
    if ($extConf->getUseTransientCache()) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['bynder2']['backend']
            = \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class;
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['bynder2']['backend']
            = \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class;
    }

    // Remove document view in extended view of FileList
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Filelist\FileList::class]['className']
        = \JWeiland\Bynder2\Xclass\FileList::class;

    $extractorRegistry = \TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
    $extractorRegistry->registerExtractionService(\JWeiland\Bynder2\Resource\BynderExtractor::class);
});
