<?php

namespace W4Services\W4Cloudinary\Command;

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use W4Services\W4Cloudinary\Tasks\Upload;

class CloudinaryCommandController extends CommandController {

    /**
     * Upload images to cloudinary
     *
     * @cli
     */
    public function uploadCommand() {

        $this->objectManager->get(
            Upload::class
        )->execute();

    }

}
