<?php

namespace Ibrows\AssociationResolver\Resolver;

use Ibrows\AssociationResolver\Result\ResultBag;
use Ibrows\AssociationResolver\Reader\AssociationAnnotationReaderInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Console\Output\OutputInterface;

interface ResolverInterface
{
    /**
     * @param AssociationAnnotationReaderInterface $annotationReader
     * @return ResolverInterface
     */
    public function setAnnotationReader(AssociationAnnotationReaderInterface $annotationReader);

    /**
     * @param EntityManager $entityManager
     * @return ResolverInterface
     */
    public function setEntityManager(EntityManager $entityManager);

    /**
     * @param ResultBag $resultBag
     * @return ResolverInterface
     */
    public function setResultBag(?ResultBag $resultBag = null);

    /**
     * @param ResolverChainInterface $resolverChain
     * @return ResolverInterface
     */
    public function setResolverChain(ResolverChainInterface $resolverChain);

    /**
     * @return ResultBag
     */
    public function getResultBag();

    /**
     * @param string $className
     * @param OutputInterface $output
     * @return ResolverInterface
     */
    public function resolveAssociations($className, ?OutputInterface $output = null);
}