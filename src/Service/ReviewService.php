<?php

namespace App\Service;

use App\Model\Review as ReviewModel;
use App\Repository\ReviewRepository;
use App\Utils\Mapper\ReviewModelMapper;


class ReviewService
{
    public function __construct(private ReviewRepository $reviewRepository,
                                private ReviewModelMapper  $reviewModelMapper )
    {
    }

    public function getReviewsByRecipe(int $id) : ReviewModel
    {
           $reviews = $this->reviewRepository->getAllByRecipeId($id);
           $review_items = array_map([$this->reviewModelMapper,"mapReview"],$reviews);
           return  (new ReviewModel($review_items));
    }



}
