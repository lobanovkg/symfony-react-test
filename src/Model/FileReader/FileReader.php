<?php
namespace App\Model\FileReader;

use App\Model\Processor\ProcessorInterface;

class FileReader
{
    private $filePath = '';

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @param ProcessorInterface $processor
     */
    public function setProcessor(ProcessorInterface $processor): void
    {
        $this->processor = $processor;
    }

    /**
     * Run file migration
     */
    public function runMigration()
    {
        $handle = fopen($this->filePath, "r");
        $firstRow = true;
        while (!feof($handle)) {
            $row = trim(fgets($handle));
            $this->processor->run($row, $firstRow);
            $firstRow = false;
        }
        fclose($handle);
    }

    /**
     * @return array
     */
    public function getProcessorsResult()
    {
        return $this->processor->getProcessorResult();
    }
}
