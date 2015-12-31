<?php

/*
 * This file is part of the DunglasApiBundle package.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dunglas\ApiBundle\Bridge\Doctrine\Orm\Extension;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Eager loads relations.
 *
 * @author Charles Sarrazin <charles@sarraz.in>
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
final class EagerLoadingExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function applyToCollection(QueryBuilder $queryBuilder, string $resourceClass, string $operationName = null)
    {
        $this->joinRelations($queryBuilder, $resourceClass);
    }

    /**
     * {@inheritdoc}
     */
    public function applyToItem(QueryBuilder $queryBuilder, string $resourceClass,  array $identifiers, string $operationName = null)
    {
        $this->joinRelations($queryBuilder, $resourceClass);
    }

    /**
     * Left joins relations to eager load.
     *
     * @param string       $resourceClass
     * @param QueryBuilder $queryBuilder
     */
    private function joinRelations(QueryBuilder $queryBuilder, string $resourceClass)
    {
        $classMetaData = $queryBuilder->getEntityManager()->getClassMetadata($resourceClass);

        foreach ($classMetaData->getAssociationNames() as $i => $association) {
            $mapping = $classMetaData->associationMappings[$association];

            if (ClassMetadataInfo::FETCH_EAGER === $mapping['fetch']) {
                $queryBuilder->leftJoin('o.'.$association, 'a'.$i);
                $queryBuilder->addSelect('a'.$i);
            }
        }
    }
}
