<?php

namespace App\Helper;


use Knp\Component\Pager\PaginatorInterface;

trait PaginatorTrait
{
    /**
     * @var PaginatorInterface|null
     */
    private $paginator;

    /**
     * @required
     */
    public function setLogger(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }

}