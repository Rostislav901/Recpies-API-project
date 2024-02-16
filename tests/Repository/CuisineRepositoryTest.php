<?php

namespace App\Tests\Repository;

use App\Entity\Cuisine;
use App\Repository\CuisineRepository;
use App\Tests\AbstractRepositoryTestCase;

class CuisineRepositoryTest extends AbstractRepositoryTestCase
{
    private CuisineRepository $repository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getRepositoryForEntity(Cuisine::class);
    }

    public function testFindByTitleAsc()
    {
           $flag  = 0;
           for( $i = 0; $i < 5; $i++ )
           {
               $cuisine =  $this->createCuisine(chr(69-$flag) ."cuisine$i","cuisine$i/slug");
               $this->entityManager->persist($cuisine);
               $flag++;
           }
           $this->entityManager->flush();

           $this->assertCount(5,$this->repository->findByTitleAsc());

           $this->assertEquals(["Acuisine4","Bcuisine3","Ccuisine2","Dcuisine1","Ecuisine0"],
                                array_map(fn(Cuisine $cuisine) => $cuisine->getTitle(),$this->repository->findByTitleAsc() ));
    }

    public function testExistByIdTrue()
    {
        $cuisine =  $this->createCuisine("cuisine","cuisine/slug");

        $this->entityManager->persist($cuisine);
        $this->entityManager->flush();
        $this->assertTrue($this->repository->existById($cuisine->getId()));
    }
    public function testExistByIdFalse()
    {

        $this->assertFalse($this->repository->existById(3));
    }

    private function createCuisine(string $title, string $slug) : Cuisine
    {
         return  (new Cuisine())
                        ->setTitle($title)
                        ->setSlug($slug);
    }
}
