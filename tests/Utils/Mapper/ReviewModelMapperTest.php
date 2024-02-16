<?php

namespace App\Tests\Utils\Mapper;

use App\Entity\Review;
use App\Entity\User;
use App\Model\ReviewItem;
use App\Tests\Utils\AbstractMapperTestCase;
use App\Utils\Mapper\ReviewModelMapper;
use DateTimeImmutable;

class ReviewModelMapperTest extends AbstractMapperTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository->expects($this->once())
            ->method("getFullNameById")
            ->with(1)
            ->willReturn("Test Name");
    }

    public function testMapReview()
    {
         $user = (new User())->setId(1);
         $time = new DateTimeImmutable();
         $reviewEntity = (new Review())
                            ->setId(8)
                            ->setUser($user)
                            ->setRating(5)
                            ->setText("testText")
                            ->setReviewPublicDate($time);

         $actual = (new ReviewModelMapper(userRepository: $this->userRepository))->mapReview($reviewEntity);

         $this->assertEquals(ReviewItem::class,get_class($actual));
         $this->assertEquals(5,$actual->getRating());
         $this->assertEquals("testText",$actual->getText());
    }
}
