<?php

namespace App\Utils\Mapper;

use App\Entity\Review;
use App\Entity\User;
use App\Model\ReviewItem;

class ReviewModelMapper extends Mapper

{
    public function mapReview(Review $review_e) : ReviewItem
    {
        /** @var User $user */
        $user = $review_e->getUser();
        return (new ReviewItem())
            ->setId($review_e->getId())
            ->setRating($review_e->getRating())
            ->setText($review_e->getText())
            ->setDate($review_e->getReviewPublicDate()->getTimestamp())
            ->setAuthor($this->getCookerFullNameByCookerId($user->getId()));
    }
}
