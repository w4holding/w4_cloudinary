<?php

namespace W4Services\W4Cloudinary\Overrides;

use Psr\EventDispatcher\EventDispatcherInterface;
use \TYPO3\CMS\Core\Resource\Index\FileIndexRepository as Typo3FileIndexRepository;
use W4Services\W4Cloudinary\Constants;

class FileIndexRepository extends Typo3FileIndexRepository {

    public function __construct(EventDispatcherInterface $eventDispatcher) {

        parent::__construct($eventDispatcher);

        $this->fields = array_merge(
            $this->fields,
            Constants::SYS_FILE_FIELDS
        );

    }

}
