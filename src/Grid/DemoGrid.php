<?php

namespace App\Grid;

use App\Entity\Customer;
use App\Entity\CustomerGroup;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Unlooped\GridBundle\ColumnType\LocalizedDateColumn;
use Unlooped\GridBundle\FilterType\AutocompleteFilterType;
use Unlooped\GridBundle\FilterType\DateRangeFilterType;
use Unlooped\GridBundle\FilterType\EntityFilterType;
use Unlooped\GridBundle\Grid\Grid;
use Unlooped\GridBundle\Helper\GridHelper;

class DemoGrid implements Grid
{
    public function configure(GridHelper $grid): void
    {
        $grid->addColumn('firstName');
        $grid->addColumn('lastName');
        $grid->addColumn('customerGroup.name');

        $grid->addColumn('createdAt', LocalizedDateColumn::class);

        $grid->addFilter('firstName');
        $grid->addFilter('lastName');
        $grid->addFilter('createdAt', DateRangeFilterType::class, [
            'view_timezone' => 'UTC',
            'show_filter' => true,
            'label' => 'Created At',
            'default_data' => DateRangeFilterType::createDefaultDataForRangeVariables('ONE_WEEK_AGO', 'TODAY'),
        ]);

        $grid->addFilter('customerGroup', AutocompleteFilterType::class, [
            'multiple' => true,
            'show_filter'          => true,
            'label'                => 'Group Autocomplete',
            // XXX: Can be service id or service class
//            'grid'                 => DemoGrid::class,
            'grid'                 => 'demo_grid',
            'entity'               => CustomerGroup::class,
            'minimum_input_length' => 1,
            'text_property'        => 'name',

            // XXX: Custom Query
//            'query_builder' => static function(EntityRepository $qb, string $term): QueryBuilder {
//                return $qb
//                    ->createQueryBuilder('e')
//                    ->where('e.name LIKE :term')
//                    ->setParameter('term', '%'.$term.'%')
//                    ->andWhere('e.id = 1')
//                ;
//            }
        ]);


        $grid->addFilter('tags', AutocompleteFilterType::class, [
            'show_filter'          => true,
            'label'                => 'Tag Autocomplete',
            'grid'                 => 'demo_grid',
            'entity'               => Tag::class,
            'minimum_input_length' => 1,
            'text_property'        => 'name',
        ]);
    }

    public function getModel(): string
    {
        return Customer::class;
    }
}
