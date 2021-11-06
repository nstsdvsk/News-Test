<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace NewsModule\News\Api\Data;

interface TestInterface
{

    const CONTENT = 'content';
    const name = 'name';
    const test_id = 'test_id';

    /**
     * Get test_id
     * @return string|null
     */
    public function getTestId();

    /**
     * Set test_id
     * @param string $testId
     * @return \Api\Data\TestInterface
     */
    public function setTestId($testId);

    /**
     * Get content
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     * @param string $content
     * @return \Api\Data\TestInterface
     */
    public function setContent($content);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set test_id
     * @param string $name
     * @return \Api\Data\TestInterface
     */
    public function setName($name);
}

