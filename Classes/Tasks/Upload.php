<?php

namespace W4Services\W4Cloudinary\Tasks;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

use W4Services\W4Cloudinary\Constants;
use W4Services\W4Cloudinary\Traits\Cloudinary;

class Upload extends AbstractTask {

    use Cloudinary {
        simplelog as originSimplelog;
    }

    /** @var ConsoleOutput */
    private $consoleOutput;

    /**
     * @param TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput $consoleOutput
     * @return Upload
     *
     * @inject
     */
    public function injectConsoleOutput( ConsoleOutput $consoleOutput) : Upload {

        $this->consoleOutput = $consoleOutput;

        return $this;

    }

    public function execute() {

        try {

            /** @var FileReference $file */
            foreach ( $this->getFileRepository()->findAll() as $file) {

                if( $file->isMissing()
                 || !!$file->getProperty( Constants::SYS_FILE_FIELD_FAILED)
                 || !preg_match( '/^image\//', $file->getMimeType())
                 || !$file->getForLocalProcessing(false)
                 || !file_exists( $file->getForLocalProcessing(false))
                 || $file->getProperty( Constants::SYS_FILE_FIELD_PUBLIC_ID) === $this->generateCloudinaryPublicId( $file)
                ) {
                    continue;
                }

                try {

                    $file->getProperty( Constants::SYS_FILE_FIELD_PUBLIC_ID)
                        ? $this->renameFile( $file)
                        : $this->uploadFile( $file);

                } catch ( \Exception $exception) {

                    $this->simplelog(
                        sprintf(
                            'Faild to upload file »%s« (%d) to cloudinary.',
                            $file->getIdentifier(),
                            $file->getUid()
                        ),
                        self::LOG_ERROR
                    );

                }

            }

        } catch ( \Exception $e) {

            return false;

        }

        return true;

    }

    protected function simplelog( string $message, int $code = 0) {

        if( $this->consoleOutput) {

            $this->consoleOutput->outputLine(
                $message
            );

        }

        $this->originSimplelog(
            $message,
            $code
        );

    }

}
