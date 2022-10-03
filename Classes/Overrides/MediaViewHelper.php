<?php

namespace W4Services\W4Cloudinary\Overrides;

use TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper as Typo3MediaViewHelper;

class MediaViewHelper extends Typo3MediaViewHelper {

    public function __construct() {

        parent::__construct();

        if( !( $this->tag instanceof TagBuilder)) {

            $this->tag = new TagBuilder();

        }

    }

}
