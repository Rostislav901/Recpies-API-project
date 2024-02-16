<?php

namespace App\Service;

use App\Entity\NewsletterClient;
use App\Exceptions\NewsletterClientExistsException;
use App\Model\NewsletterClient as NewsletterClientModel;
use App\Repository\NewsletterRepository;

class NewsletterService
{
   public function __construct(
                               private NewsletterRepository $repository,
                               )
   {}
   public function subscribe(NewsletterClientModel $newsletterClient) : void
   {
         if($this->repository->existsByEmail($newsletterClient->getEmail()))
         {
             throw new NewsletterClientExistsException();
         }
         $letter = new NewsletterClient();
         $letter->setEmail($newsletterClient->getEmail());

         $this->repository->saveSubscribe($letter);
   }
}
