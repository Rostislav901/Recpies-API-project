<?php

namespace App\Model;

class Review
{

    /**
     * @param ReviewItem[]$reviews
     */
     public function __construct(private array $reviews)
     {
     }

    /**
     * @return ReviewItem[]
     */
    public function getReviews(): array
    {
        return $this->reviews;
    }


}
