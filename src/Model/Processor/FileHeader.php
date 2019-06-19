<?php
namespace App\Model\Processor;

/**
 * Class FileHeader
 */
class FileHeader
{
    private $headerAssociation = [];

    /**
     * @param array $arguments
     */
    public function firstRow(array $arguments)
    {
        foreach ($arguments as $argument) {
            if ('' === $argument) {
                continue;
            }
            $this->headerAssociation[] = strtolower($argument);
        }
    }

    /**
     * @return array
     */
    public function getHeaderAssociation()
    {
        return $this->headerAssociation;
    }
}
