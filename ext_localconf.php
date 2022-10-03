<?php

defined('TYPO3_MODE') or die();

( function( $_EXTKEY) {
    $configuration = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    )->get('w4_cloudinary') ?: [];

    $cloudinaryConfigKeys = [
        'cloud_name',
        'api_key',
        'api_secret'
    ];

    /* configure cloudinary */
    \Cloudinary::config(
        array_filter(
            $configuration,
            function ($key) use ($cloudinaryConfigKeys) {
                return in_array(
                    $key,
                    $cloudinaryConfigKeys
                );
            },
            ARRAY_FILTER_USE_KEY
        )
    );

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ \TYPO3\CMS\Core\Resource\Index\FileIndexRepository::class] = [
        'className' => \W4Services\W4Cloudinary\Overrides\FileIndexRepository::class
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ \TYPO3\CMS\Extbase\Service\ImageService::class] = [
        'className' => \W4Services\W4Cloudinary\Services\ImageService::class
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ \TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder::class] = [
        'className' => \W4Services\W4Cloudinary\Overrides\TagBuilder::class
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper::class] = [
        'className' => \W4Services\W4Cloudinary\Overrides\ImageViewHelper::class
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][ \TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper::class] = [
        'className' => \W4Services\W4Cloudinary\Overrides\MediaViewHelper::class
    ];

    // Add Upload task
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][ \W4Services\W4Cloudinary\Tasks\Upload::class] = array(
        'extension' => 'w4_cloudinary',
        'title' => 'Upload images to Cloudinary',
        'description' => 'Upload images to Cloudinary',
    );

    if (TYPO3_MODE === 'BE') {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] =
            \W4Services\W4Cloudinary\Command\CloudinaryCommandController::class;
    }

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][]
        = \W4Services\W4Cloudinary\Hooks\PageRenderPreProcessor::class . '->render_preProcess';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['createHashBase'][] =
        \W4Services\W4Cloudinary\Hooks\PageService::class . '->setCacheBurstIfIpExcluded';

})( 'w4_cloudinary');
