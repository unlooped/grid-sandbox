<?php

namespace App\Controller;

use App\Grid\DemoGrid;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Unlooped\GridBundle\Exception\DuplicateColumnException;
use Unlooped\GridBundle\Exception\DuplicateFilterException;
use Unlooped\GridBundle\Exception\TypeNotAColumnException;
use Unlooped\GridBundle\Exception\TypeNotAFilterException;
use Unlooped\GridBundle\Service\GridService;

class DemoController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/{filterHash}", name="index.filter")
     *
     * @throws DuplicateColumnException
     * @throws DuplicateFilterException
     * @throws NonUniqueResultException
     * @throws ReflectionException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TypeNotAColumnException
     * @throws TypeNotAFilterException
     */
    public function index(
        GridService $gridService,
        ?string $filterHash = null,
        DemoGrid $grid
    ): Response
    {
        $gh = $gridService->getGridHelper($grid->getModel(), [
            'title'             => 'Customers',
            'allow_save_filter' => true,
        ], $filterHash);

        $grid->configure($gh);

        return $gridService->render('default/grid.html.twig', $gh);
    }
}
