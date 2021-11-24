<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace NewsModule\News\Model;

use NewsModule\News\Api\Data\TestInterface;
use NewsModule\News\Model\Test\FileInfo;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;

class Test extends \Magento\Framework\Model\AbstractModel implements TestInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\NewsModule\News\Model\ResourceModel\Test::class);
    }

    /**
     * Get test_id
     * @return string|null
     */
    public function getTestId()
    {
        return $this->getData('test_id');
    }

    /**
     * Set test_id
     * @param string $testId
     * @return \NewsModule\News\Api\Data\TestInterface
     */
    public function setTestId($testId)
    {
        return $this->setData(self::test_id, $testId);
    }

    /**
     * Get content
     * @return string|null
     */
    public function getContent()
    {
        return $this->getData('content');
    }

    /**
     * Set content
     * @param string $content
     * @return \NewsModule\News\Api\Data\TestInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @param string $testId
     * @return \NewsModule\News\Api\Data\TestInterface
     */
    public function setName($name)
    {
        return $this->setData(self::name, $name);
    }

    /**
     * @param string $imageName
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getImageUrl($imageName = null)
    {
        $url = '';
        $image = $imageName;
        if (!$image) {
            $image = $this->getData('image');
        }
        if ($image) {
            if (is_string($image)) {
                $url = $this->_getStoreManager()->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ).FileInfo::ENTITY_MEDIA_PATH .'/'. $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    /**
     * @return StoreManagerInterface
     */
    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }
}

