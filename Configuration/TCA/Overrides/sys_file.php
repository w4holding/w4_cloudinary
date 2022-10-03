<?php

defined('TYPO3') or die();

(function( array &$TCA) {

        $tempColumns = [
            \W4Services\W4Cloudinary\Constants::SYS_FILE_FIELD_PUBLIC_ID => [
                'exclude' => true,
                'l10n_mode' => 'exclude',
                'label' => 'Public ID',
                'config' => [
                    'type' => 'input',
                ]
            ],
            \W4Services\W4Cloudinary\Constants::SYS_FILE_FIELD_FAILED => [
                'exclude' => true,
                'l10n_mode' => 'exclude',
                'label' => 'failed',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                ]
            ],
            \W4Services\W4Cloudinary\Constants::SYS_FILE_FIELD_URL => [
                'exclude' => true,
                'l10n_mode' => 'exclude',
                'label' => 'URL',
                'config' => [
                    'type' => 'input',
                ]
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns( 'sys_file', $tempColumns);
        
})( $GLOBALS['TCA']['sys_file']);
