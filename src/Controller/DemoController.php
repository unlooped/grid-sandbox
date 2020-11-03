<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\NonUniqueResultException;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Unlooped\GridBundle\ColumnType\LocalizedDateColumn;
use Unlooped\GridBundle\Exception\DuplicateColumnException;
use Unlooped\GridBundle\Exception\DuplicateFilterException;
use Unlooped\GridBundle\FilterType\DateRangeFilterType;
use Unlooped\GridBundle\Service\GridService;

class DemoController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/{filterHash}", name="index.filter")
     *
     * @throws NonUniqueResultException
     * @throws ReflectionException
     * @throws DuplicateColumnException
     * @throws DuplicateFilterException
     */
    public function index(
        GridService $gridService,
        string $filterHash = null
    ): Response
    {
        $gh = $gridService->getGridHelper(Customer::class, [
            'title' => 'Customers',
        ], $filterHash);

        $gh->addColumn('firstName');
        $gh->addColumn('lastName');
        $gh->addColumn('createdAt', LocalizedDateColumn::class);

        $gh->addFilter('firstName');
        $gh->addFilter('lastName');
        $gh->addFilter('createdAt', DateRangeFilterType::class, [
            'view_timezone' => 'UTC',
            'show_filter' => true,
            'label' => 'Created At',
            'default_data' => DateRangeFilterType::createDefaultDataForRangeVariables('ONE_WEEK_AGO', 'TODAY'),
        ]);

        return $gridService->render('default/grid.html.twig', $gh);
    }
}