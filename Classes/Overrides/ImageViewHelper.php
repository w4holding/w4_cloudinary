<?php

namespace W4Services\W4Cloudinary\Overrides;

use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper as Typo3ImageViewHelper;

class ImageViewHelper extends Typo3ImageViewHelper {

    public function __construct() {

        parent::__construct();

        if( !( $this->tag instanceof TagBuilder)) {

            $this->tag = new TagBuilder();

        }

    }

}
