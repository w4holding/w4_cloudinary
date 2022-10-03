<?php

namespace W4Services\W4Cloudinary\Hooks;

use TYPO3\CMS\Core\Page\PageRenderer;
use W4Services\W4Cloudinary\Traits\Configuration;

class PageRenderPreProcessor {

    use Configuration;

    /**
     * @param array $params
     * @param PageRenderer $pageRenderer
     */
    public function render_preProcess( array &$params, PageRenderer $pageRenderer) {

        if(
            TYPO3_MODE !== 'BE'
         && !!$this->getConfiguration()['processing_responsive']
         && !$this->excludedByIp()
        ) {

            $pageRenderer->addJsFile(
                '/typo3conf/ext/w4_cloudinary/Resources/Public/Javascript/lib/cloudinary-core-shrinkwrap.min.js',
                null,
                TRUE,
                TRUE
            );

            $cloudName = \Cloudinary::config_get( 'cloud_name');

            $pageRenderer->addJsFooterInlineCode(
                'cloudinary-core-shrinkwrap-'.$cloudName,
                <<<JS
(function(){
    var cl = cloudinary.Cloudinary.new({cloud_name: "$cloudName"});
        cl.config( {
            responsive_use_breakpoints: "resize"
        });
        cl.responsive();
})();
JS
            );

        }

    }

}
