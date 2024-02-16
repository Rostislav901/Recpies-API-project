<?php

namespace App\Tests\Service;

use App\Entity\Review;
use App\Model\ReviewItem;
use App\Repository\ReviewRepository;
use App\Service\ReviewService;
use App\Utils\Mapper\ReviewModelMapper;
use PHPUnit\Framework\TestCase;
use App\Model\Review as ReviewModel;

class ReviewServiceTest extends TestCase
{

    public function testGetReviewsByRecipe()
    {
       $repository = $this->createMock(ReviewRepository::class);
       $mapper = $this->createMock(ReviewModelMapper::class);
       $review = (new Review())->setText("testText")->setRating(5);

       $repository->expects($this->once())
           ->method("getAllByRecipeId")
           ->with(3)
           ->willReturn([$review]);

       $mapper->expects($this->once())
           ->method("mapReview")
           ->with($review)
           ->willReturn((new ReviewItem())->setRating(5)->setText("testText")->setAuthor("Piter"));

       $actual = (new ReviewService($repository,$mapper))->getReviewsByRecipe(3);

       $this->assertEquals(ReviewModel::class,get_class($actual));
       $this->assertEquals(ReviewItem::class,get_class($actual->getReviews()[0]));
       $this->assertCount(1,$actual->getReviews());
       $this->assertEquals(5,$actual->getReviews()[0]->getRating());
    }
}
