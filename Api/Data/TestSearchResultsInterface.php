<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace NewsModule\News\Api\Data;

interface TestSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get test list.
     * @return \NewsModule\News\Api\Data\TestInterface[]
     */
    public function getItems();

    /**
     * Set content list.
     * @param \NewsModule\News\Api\Data\TestInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

