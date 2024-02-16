<?php

namespace App\Tests\Utils;

use App\Entity\Recipe;
use App\Repository\ReviewRepository;
use App\Utils\MethodHelper;
use PHPUnit\Framework\TestCase;

class MethodHelperTest extends TestCase
{
    private ReviewRepository $reviewRepository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);

        $this->reviewRepository->expects($this->exactly(2))
            ->method("getCountByRecipeId")
            ->withConsecutive([3],[4])
            ->willReturn(3);
    }

    public function testGetTotalReviewCount()
    {
        $recipes = [(new Recipe())->setId(3),(new Recipe())->setId(4)];

        $actual = (new MethodHelper($this->reviewRepository))->getTotalReviewCount($recipes);
        $this->assertIsInt($actual);
        $this->assertEquals(6,$actual);
    }

    public function testCalculateAverageRating()
    {
        $recipes = [(new Recipe())->setId(3),(new Recipe())->setId(4)];

        $this->reviewRepository->expects($this->exactly(2))
                                ->method("getRating")
                                ->withConsecutive([3],[4])
//                                ->withAnyParameters()
                                ->willReturn(8);

        $actual = (new MethodHelper($this->reviewRepository))->calculateAverageRating($recipes);

        $this->assertIsFloat($actual);
        $this->assertEquals(2.7,$actual);
    }
}
