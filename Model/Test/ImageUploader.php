<?php

namespace NewsModule\News\Model\Test;

use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Exception\LocalizedException;

class ImageUploader {

    /**
     * @var Database
     */
    protected $coreFileStorageDatabase;

    /**
     * @var WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $baseTmpPath;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string|array
     */
    protected $allowedExtensions;

    /**
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param string $baseTmpPath
     * @param string $basePath
     * @param string[] $allowedExtensions
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        $baseTmpPath = 'news/tmp/images',
        $basePath = 'news/images',
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png']
    )
    {
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * @param $baseTmpPath
     * @return void
     */
    public function setBaseTmpPath ($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * @param $basePath
     * @return void
     */
    public function setBasePath ($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param $allowedExtensions
     * @return void
     */
    public function setAllowedExtensions ($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * @return string
     */
    public function getBaseTmpPath ()
    {
        return $this->baseTmpPath;
    }

    /**
     * @return string
     */
    public function getBasePath ()
    {
        return $this->basePath;
    }

    /**
     * @return string|array
     */
    public function getAllowedExtensions ()
    {
        return $this->allowedExtensions;
    }

    /**
     * @param $path
     * @param $imageName
     * @return string
     */
    public function getFilePath ($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * @param $imageName
     * @return mixed
     * @throws LocalizedException
     */
    public function moveFileFromTmp ($imageName) {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            $this->coreFileStorageDatabase->copyFile($baseTmpImagePath, $baseImagePath);
            $this->mediaDirectory->renameFile($baseTmpImagePath, $baseImagePath);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Something went wrong with file(s).'));
        }

        return $imageName;
    }

    /**
     * @param $fileId
     * @return string|array
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saveFileToTmpDir ($fileId)
    {
        $baseTmpPath = $this->getBaseTmpPath();

        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);

        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        unset($result['path']);

        if (!$result) {
            throw new LocalizedException(__('File can not be saved to the destination folder.'));
        }

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['url'] = $this->storeManager
            ->getStore()
            ->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];

        if(isset($result['file']))
        {
            try {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new LocalizedException(__('Something went wrong while saving the file(s).'));
            }
        }

        return $result;
    }
}
