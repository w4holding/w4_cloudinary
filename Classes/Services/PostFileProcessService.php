<?php

namespace W4Services\W4Cloudinary\Services;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\SingletonInterface;

use W4Services\W4Cloudinary\Traits\Cloudinary;
use W4Services\W4Cloudinary\Traits\Configuration;

class PostFileProcessService implements SingletonInterface
{

    use Cloudinary,
        Configuration;

    const SUPPORTED_MIME_TYPES = [
        'image'
    ];

    public function __construct()
    {
        $this->setInvalidateCdnOnChanges(
            $this->getConfiguration()['invalidate_cdn_on_changes']
        );
    }

    /**
     * @param FileInterface $file
     * @param $localFilePath
     */
    public function postFileAdd(FileInterface $file)
    {

        if (!$this->getConfiguration()['processing_on_the_fly'] || !$this->isSupported($file)) {
            return;
        }

        $this->uploadFile(
            $file instanceof File ? $file : $file->getOriginalFile()
        );

    }

    /**
     * @param FileInterface $file
     * @param $localFilePath
     */
    public function postFileReplace(FileInterface $file)
    {

        $this->postFileAdd($file);

    }

    /**
     * @param FileInterface $file
     * @param $localFilePath
     */
    public function postFileRename(FileInterface $file)
    {

        if (!$this->isSupported($file)) {
            return;
        }

        $this->renameFile(
            $file instanceof File ? $file : $file->getOriginalFile()
        );

    }

    /**
     * @param FileInterface $file
     * @param $localFilePath
     */
    public function postFileDelete(FileInterface $file)
    {

        if (!$this->isSupported($file)) {
            return;
        }

        $this->deleteFile(
            $file instanceof File ? $file : $file->getOriginalFile()
        );

    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    protected function isSupported(FileInterface $file)
    {

        $mimeType = $file->getProperty('mime_type')
            ?: $file->getOriginalFile()->getProperty('mime_type');

        return $file instanceof File && (
                in_array(
                    $mimeType,
                    self::SUPPORTED_MIME_TYPES
                )
                || in_array(
                    array_shift(
                        explode(
                            '/',
                            $mimeType
                        )
                    ),
                    self::SUPPORTED_MIME_TYPES
                )
            );

    }

}
