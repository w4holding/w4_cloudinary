<?php

namespace W4Services\W4Cloudinary\Traits;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait Configuration {

    /**
     * @var string
     */
    private static $EXTENSION_KEY = 'w4_cloudinary';

    /**
     * @var array
     */
    private static $configuration;

    /**
     * @var array
     */
    private static $excludeByIp = [];

    /**
    /**
     * @return array
     */
    protected function getConfiguration() : array {
        if (!self::$configuration) {

            self::$configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
                ->get(self::$EXTENSION_KEY);

        }

        return self::$configuration;
    }

    protected function processViaJavascript() {
        return !!$this->getConfiguration()['processing_responsive'];
    }

    protected function getFormat() {
        return $this->getConfiguration()['format'];
    }

    /**
     * Check if current client IP should ignored for cloudinary output
     *
     * @return bool
     */
    protected function excludedByIp() : bool {
        // no REMOTE_ADDR available
        if(
            !($ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING))
        ) {
            return FALSE;
        }

        if (!array_key_exists($ip, self::$excludeByIp)) {
            $excludes = array_map( function($q) {
                return sprintf(
                    '/%s/',
                    trim($q),
                );
            }, array_filter(
                explode(';', trim($this->getConfiguration()['exclude_ips'])) ?: [],
            'strlen'
            ));

            self::$excludeByIp[$ip] = FALSE;

            foreach ($excludes as $exclude) {
                if(preg_match($exclude, $ip)) {
                    self::$excludeByIp[$ip] = TRUE;

                    break;
                }
            }
        }

        return self::$excludeByIp[$ip];
    }

}
