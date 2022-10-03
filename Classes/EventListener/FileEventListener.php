<?php

namespace W4Services\W4Cloudinary\EventListener;

use TYPO3\CMS\Core\Resource\Event\AfterFileAddedEvent;
use TYPO3\CMS\Core\Resource\Event\AfterFileDeletedEvent;
use TYPO3\CMS\Core\Resource\Event\AfterFileRenamedEvent;
use TYPO3\CMS\Core\Resource\Event\AfterFileReplacedEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use W4Services\W4Cloudinary\Services\PostFileProcessService;

class FileEventListener {
    private PostFileProcessService $postFileProcessService;

    public function __construct() {
        $this->postFileProcessService = GeneralUtility::makeInstance(
            PostFileProcessService::class
        );
    }

    public function afterFileAddedEvent(AfterFileAddedEvent $event): void {
        $this->getPostFileProcessService()->postFileAdd(
            $event->getFile()
        );
    }

    public function afterFileDeletedEvent(AfterFileDeletedEvent $event): void {
        $this->getPostFileProcessService()->postFileDelete(
            $event->getFile()
        );
    }

    public function afterFileRenamedEvent(AfterFileRenamedEvent $event): void {
        $this->getPostFileProcessService()->postFileRename(
            $event->getFile()
        );
    }

    public function afterFileReplacedEvent(AfterFileReplacedEvent $event): void {
        $this->getPostFileProcessService()->postFileReplace(
            $event->getFile()
        );
    }

    /**
     * @return PostFileProcessService
     */
    protected function getPostFileProcessService() : PostFileProcessService {
        return $this->postFileProcessService;
    }
}
