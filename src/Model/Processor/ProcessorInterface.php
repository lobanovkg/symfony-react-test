<?php

namespace App\Model\Processor;

/**
 * Interface ProcessorInterface
 */
interface ProcessorInterface
{
    /**
     * @param string $row
     * @param bool   $firstRow
     */
    public function run(string $row, bool $firstRow): void;

    /**
     * @return array
     */
    public function getProcessorResult(): array;
}
