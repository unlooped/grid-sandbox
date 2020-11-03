<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\CustomerGroup;
use App\Repository\CustomerGroupRepository;
use Doctrine\ORM\NonUniqueResultException;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Unlooped\GridBundle\ColumnType\LocalizedDateColumn;
use Unlooped\GridBundle\Exception\DuplicateColumnException;
use Unlooped\GridBundle\Exception\DuplicateFilterException;
use Unlooped\GridBundle\FilterType\AutocompleteFilterType;
use Unlooped\GridBundle\FilterType\ChoiceFilterType;
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
        CustomerGroupRepository $customerGroupRepository,
        string $filterHash = null
    ): Response
    {
        $gh = $gridService->getGridHelper(Customer::class, [
            'title'             => 'Customers',
            'allow_save_filter' => true,
        ], $filterHash);

        $gh->addColumn('firstName');
        $gh->addColumn('lastName');
        $gh->addColumn('customerGroup.name');
        $gh->addColumn('createdAt', LocalizedDateColumn::class);


        $availableGroups = [];
        foreach ($customerGroupRepository->findAll() as $group) {
            $fullName = $group->getName();
            $availableGroups[$fullName] = $fullName;
        }

        $gh->addFilter('firstName');
        $gh->addFilter('lastName');
        $gh->addFilter('createdAt', DateRangeFilterType::class, [
            'view_timezone' => 'UTC',
            'show_filter' => true,
            'label' => 'Created At',
            'default_data' => DateRangeFilterType::createDefaultDataForRangeVariables('ONE_WEEK_AGO', 'TODAY'),
        ]);
        $gh->addFilter('customerGroup.name', ChoiceFilterType::class, [
            'show_filter' => true,
            'label'       => 'Group',
            'choices'     => $availableGroups,
        ]);

        $gh->addFilter('customerGroup', AutocompleteFilterType::class, [
            'show_filter'          => true,
            'label'                => 'Group Autocomplete',
            'route'                => 'xhr_groups',
            'entity'               => CustomerGroup::class,
            'minimum_input_length' => 1,
            'text_property'        => 'name',
        ]);


        return $gridService->render('default/grid.html.twig', $gh);
    }

    /**
     * @Route("/xhr/groups", name="xhr_groups")
     */
    public function xhrGroups(
        CustomerGroupRepository $customerGroupRepository,
        Request $request
    )
    {
        $term = $request->get('q');

        $countQB = $customerGroupRepository->createQueryBuilder('e');
        $countQB
            ->select($countQB->expr()->count('e'))
            ->setParameter('term', '%' . $term . '%')
        ;

        $maxResults = 10;
        $offset = ($request->get('page', 1) - 1) * $maxResults;

        $resultQb = $customerGroupRepository->createQueryBuilder('e');
        $resultQb
            ->setParameter('term', '%' . $term . '%')
            ->setMaxResults($maxResults)
            ->setFirstResult($offset)
        ;

        $countQB->where('e.name LIKE :term');
        $resultQb->where('e.name LIKE :term');

        $count = $countQB->getQuery()->getSingleScalarResult();
        $paginationResults = $resultQb->getQuery()->getResult();

        $result = ['results' => null, 'more' => $count > ($offset + $maxResults)];

        $accessor = PropertyAccess::createPropertyAccessor();

        $result['results'] = array_map(static function ($item) use ($accessor) {
            return ['id' => $accessor->getValue($item, 'id'), 'text' => $accessor->getValue($item, 'name')];
        }, $paginationResults);

        return new JsonResponse($result);
    }
}
