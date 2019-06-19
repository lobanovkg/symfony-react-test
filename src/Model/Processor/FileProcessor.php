<?php

namespace App\Model\Processor;

use App\Entity\Apartment;
use Doctrine\ORM\EntityManager;

/**
 * Class FileProcessor
 */
class FileProcessor implements ProcessorInterface
{
    /**
     * @var FileHeader
     */
    private $fileHeader;

    private $em;

    private $processorLog = ['rowsCount' => 0, 'errorsCount' => 0, 'newRowsCount' => 0, 'existRowsCount' => 0, 'errorMessage' => ''];

    /**
     * FileProcessor constructor.
     *
     * @param FileHeader    $fileHeader
     * @param EntityManager $em
     */
    public function __construct(FileHeader $fileHeader, EntityManager $em)
    {
        $this->fileHeader = $fileHeader;
        $this->em         = $em;
    }


    /**
     * @param string $row
     * @param bool   $firstRow
     */
    public function run(string $row, bool $firstRow): void
    {
        $explodedRow = explode(',', $row);

        if (true === $firstRow) {
            $this->fileHeader->firstRow($explodedRow);

            return;
        }

        $headerAssociations = $this->fileHeader->getHeaderAssociation();

        $valueRelationships = [];
        try {
            foreach ($explodedRow as $index => $item) {
                $index = strtolower($index);
                if (!isset($headerAssociations[$index])) {
                    continue;
                }
                $valueRelationships[$headerAssociations[$index]] = $item;
            }
        } catch (\Throwable $e) {
            $this->processorLog['errorsCount'] = $this->processorLog['errorsCount'] + 1;
            $this->processorLog['errorMessage'] = $e->getMessage();

            return;
        }

        $this->processorLog['rowsCount'] = $this->processorLog['rowsCount'] + 1;


        try {
            $this->doApartment($valueRelationships);
        } catch (\Throwable $e) {
            $this->processorLog['errorsCount'] = $this->processorLog['errorsCount'] + 1;
            $this->processorLog['errorMessage'] = $e->getMessage();

            return;
        }
    }

    /**
     * @return array
     */
    public function getProcessorResult(): array
    {
        return $this->processorLog;
    }

    private function doApartment(array $valueRelationships)
    {
        $apartmentNew = new Apartment();
        /**
         * @todo: add batch inserting
         * @todo: check equal rows
         */
        try {
            foreach ($valueRelationships as $key => $valueRelationship) {
                $method = 'set'.ucfirst($key);
                if (method_exists($apartmentNew, $method)) {
                    if ('' === trim($valueRelationship)) {
                        $valueRelationship = null;
                    }
                    $apartmentNew->{$method}($valueRelationship);
                }
            }

            $this->em->persist($apartmentNew);
            $this->em->flush();
            $this->em->clear();
        } catch (\Throwable $e) {
            $this->processorLog['errorMessage'] = $e->getMessage();

            return 0;
        }

        $this->processorLog['newRowsCount'] = $this->processorLog['newRowsCount'] + 1;

        return (int) $apartmentNew->getId();
    }
}
