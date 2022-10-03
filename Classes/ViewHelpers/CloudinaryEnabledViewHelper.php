<?php

namespace W4Services\W4Cloudinary\ViewHelpers;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use W4Services\W4Cloudinary\Traits\Configuration;

class CloudinaryEnabledViewHelper extends AbstractViewHelper implements SingletonInterface {

    use Configuration;

    /**
     * @var null CloudinaryEnabledViewHelper
     */
    private static $instance = null;

    public static function renderStatic(
        array $arguments, \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return !self::getInstance()->excludedByIp();
    }

    public static function getInstance() : CloudinaryEnabledViewHelper {
        if (!self::$instance) {
            self::$instance = GeneralUtility::makeInstance(
                ObjectManager::class
            )->get(
                self::class
            );
        }

        return self::$instance;
    }

}
