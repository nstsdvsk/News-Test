<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace NewsModule\News\Model;

use NewsModule\News\Api\Data\TestInterface;

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
        return $this->_get(self::TEST_ID);
    }

    /**
     * Set test_id
     * @param string $testId
     * @return \NewsModule\News\Api\Data\TestInterface
     */
    public function setTestId($testId)
    {
        return $this->setData(self::TEST_ID, $testId);
    }

    /**
     * Get content
     * @return string|null
     */
    public function getContent()
    {
        return $this->_get(self::CONTENT);
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
}

