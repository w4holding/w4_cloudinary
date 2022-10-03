<?php
namespace W4Services\W4Cloudinary\Hooks;

use W4Services\W4Cloudinary\Traits\Configuration;

class PageService {

    const CLOUDINARY_CACHE_BURST_PARAMETER = 'cloudinary';

    const CLOUDINARY_CACHE_BURST_PARAMETER_VALUE = 'off';

    use Configuration;

    /**
     * If current IP should get Images rendered without cloudinary then we'll add a cache burst here.
     *
     * @param $params
     * @param $pObj
     */
    public function setCacheBurstIfIpExcluded(&$params, &$pObj) {
        if($this->excludedByIp()) {
            $params['hashParameters'][self::CLOUDINARY_CACHE_BURST_PARAMETER]
                = self::CLOUDINARY_CACHE_BURST_PARAMETER_VALUE;
        }
    }

}
