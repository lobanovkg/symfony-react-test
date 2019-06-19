<?php
/**
 * Created by PhpStorm.
 * User: Lobanov Kyryll
 * Date: 14.06.19
 * Time: 18:42
 */

namespace App\Model\ElasticsearchMigration\Type;

class Apartment extends AbstractType
{
    const APARTMENT_INDEX_NAME = 'apartments';

    public function run()
    {
        $this->es->deleteIndex(self::APARTMENT_INDEX_NAME);
        $this->es->createIndex(self::APARTMENT_INDEX_NAME);

        $apartmentRepository = $this->em->getRepository(\App\Entity\Apartment::class);
        $apartments          = $apartmentRepository->getAllApartments();

        $this->es->insert(self::APARTMENT_INDEX_NAME, self::APARTMENT_INDEX_NAME, $apartments);
    }
}
