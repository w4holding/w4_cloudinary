<?php

namespace W4Services\W4Cloudinary\Traits;

use TYPO3\CMS\Core\Authentication\CommandLineUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use W4Services\W4Cloudinary\Constants;

trait Cloudinary {

    protected static $LOG_ERROR = FlashMessage::ERROR;

    /** @var ConnectionPool */
    private static $databaseConnection;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    private $invalidateCdnOnChanges = FALSE;

    /**
     * @param TYPO3\CMS\Extbase\Object\ObjectManager $objectManager
     */
    public function injectObjectManager( ObjectManager $objectManager) {

        $this->objectManager = $objectManager;

    }

    /**
     * @return ObjectManager
     */
    protected function getObjectManager() : ObjectManager {

        if( !$this->objectManager) {

            $this->objectManager = GeneralUtility::makeInstance(
                ObjectManager::class
            );

        }

        return $this->objectManager;

    }

    /**
     * @return FileRepository
     */
    protected function getFileRepository() : FileRepository {

        return GeneralUtility::makeInstance(
            FileRepository::class
        );

    }

    /**
     * @param FileInterface $file
     * @return string
     */
    private function generateCloudinaryPublicId( File $file) : string {

        return trim(
            // [^\.] added to avoid problems with . character in path/folder names
            preg_replace(
                '/\.(?:.[^\.]+?)$/',
                '',
                $file->getIdentifier()
            ),
            '/'
        );

    }

    /**
     * @param File $file
     * @return bool
     */
    protected function uploadFile( File $file) : bool {

        try {

            $response = \Cloudinary\Uploader::upload(
                $file->getForLocalProcessing(false),
                [
                    Constants::CLOUDINARY_FIELD_PUBLIC_ID => $this->generateCloudinaryPublicId(
                        $file
                    ),
                    'use_filename' => TRUE,
                    'invalidate' => $this->invalidateCdnOnChanges
                ]
            );

            $this->updateFileRecord(
                $file->getUid(),
                [
                    Constants::SYS_FILE_FIELD_PUBLIC_ID => $response[ Constants::CLOUDINARY_FIELD_PUBLIC_ID],
                    Constants::SYS_FILE_FIELD_URL => $response[ Constants::CLOUDINARY_FIELD_URL],
                    Constants::SYS_FILE_FIELD_FAILED => 0
                ]
            );

            $this->simplelog(
                sprintf(
                    'Uploaded file »%s« (%d) to cloudinary.',
                    $file->getIdentifier(),
                    $file->getUid()
                )
            );
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->invalidateCdnOnChanges, '');

            return TRUE;

        } catch ( \Exception $e) {

            $this->updateFileRecord(
                $file->getUid(),
                [
                    Constants::SYS_FILE_FIELD_FAILED => 1
                ]
            );

            $this->simplelog(
                sprintf(
                    'Error while upload file »%s« (%d) to cloudinary. Message: %s',
                    $file->getIdentifier(),
                    $file->getUid(),
                    $e->getMessage()
                ),
                self::$LOG_ERROR
            );

        }

        return FALSE;

    }

    /**
     * @param File $file
     * @return mixed
     */
    protected function renameFile( File $file) : bool {

        try {

            $response = \Cloudinary\Uploader::rename(
                $file->getProperty( Constants::SYS_FILE_FIELD_PUBLIC_ID),
                $this->generateCloudinaryPublicId(
                    $file
                )
            );

            $this->updateFileRecord(
                $file->getUid(),
                [
                    Constants::SYS_FILE_FIELD_PUBLIC_ID => $response[ Constants::CLOUDINARY_FIELD_PUBLIC_ID],
                    Constants::SYS_FILE_FIELD_URL => $response[ Constants::CLOUDINARY_FIELD_URL],
                    Constants::SYS_FILE_FIELD_FAILED => 0
                ]
            );

            $this->simplelog(
                sprintf(
                    'Rename file »%s« (%d) to cloudinary.',
                    $file->getIdentifier(),
                    $file->getUid()
                )
            );

            return TRUE;

        } catch ( \Exception $e) {

            $this->updateFileRecord(
                $file->getUid(),
                [
                    Constants::SYS_FILE_FIELD_FAILED => 1
                ]
            );

            $this->simplelog(
                sprintf(
                    'Error while rename file »%s« (%d) to cloudinary. Message: %s',
                    $file->getIdentifier(),
                    $file->getUid(),
                    $e->getMessage()
                ),
                self::$LOG_ERROR
            );

        }

        return FALSE;

    }

    protected function deleteFile( File $file) : bool {

        try {

            $identifier = $file->getProperty( Constants::SYS_FILE_FIELD_PUBLIC_ID);

            if( !!$identifier) {

                \Cloudinary\Uploader::destroy(
                    $identifier,
                );

                $this->simplelog(
                    sprintf('File »%s« deleted from cloudinary.', $file->getIdentifier())
                );

                return TRUE;

            }

        } catch ( \Exception $e) {

            $this->simplelog(
                sprintf(
                    'Faild to delete file »%s« from cloudinary. Message: %s',
                    $file->getIdentifier(),
                    $file->getUid(),
                    $e->getMessage()
                ),
                self::$LOG_ERROR
            );

        }

        return FALSE;

    }

    protected function simplelog( string $message, int $code = 0) {

        if(
            TYPO3_MODE == 'BE'
      && !( $GLOBALS['BE_USER'] instanceof CommandLineUserAuthentication)
        ) {

            /** @var FlashMessageService $flashMessageService */
            $flashMessageService = $this->getObjectManager()->get(
                FlashMessageService::class
            );

            /** @var FlashMessageQueue $messageQueue */
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();

            $messageQueue->enqueue(
                GeneralUtility::makeInstance(
                    FlashMessage::class,
                    $message,
                    '',
                    $code
                ),
                TRUE
            );

        }

        $GLOBALS['BE_USER']->writelog(4, 0, $code, 0, '[w4_cloudinary] ' . $message, []);

    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder() : QueryBuilder {

        return $this->getDatabaseConnection()
            ->getQueryBuilderForTable( 'sys_file');

    }

    /**
     * @return ConnectionPool
     */
    protected function getDatabaseConnection() : ConnectionPool {

        if( !self::$databaseConnection) {

            self::$databaseConnection = $this->getObjectManager()
                ->get( ConnectionPool::class);

        }

        return self::$databaseConnection;

    }

    /**
     * @param $uid
     * @param array $columns
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    protected function updateFileRecord( $uid, array $columns) {

        $queryBuilder = $this->getQueryBuilder()
            ->update( 'sys_file');

        foreach ( $columns as $column => $value) {

            $queryBuilder->set( $column, $value);

        }

        $queryBuilder->andWhere( 'uid = :uid')
            ->setParameter( 'uid', $uid);

        return $queryBuilder->execute();

    }

    /**
     * @param bool $invalidateCdnOnChanges
     */
    public function setInvalidateCdnOnChanges(bool $invalidateCdnOnChanges)
    {
        $this->invalidateCdnOnChanges = $invalidateCdnOnChanges;
    }

}
