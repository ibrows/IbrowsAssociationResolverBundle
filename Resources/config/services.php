<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Ibrows\AssociationResolver\Reader\AssociationAnnotationReader;
use Ibrows\AssociationResolver\Resolver\Resolver;
use Ibrows\AssociationResolver\Resolver\ResolverChain;
use Ibrows\AssociationResolver\Resolver\Type\ManyToMany;
use Ibrows\AssociationResolver\Resolver\Type\ManyToOne;
use Ibrows\AssociationResolver\Resolver\Type\OneToMany;
use Ibrows\AssociationResolver\Resolver\Type\OneToOne;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('ibrows_associationresolver.softdelete', true)
        ->set('ibrows_associationresolver.softdeletegetter', 'getDeletedAt');

    $services = $container->services();

    $services->set('ibrows_associationresolver.resolver', Resolver::class)
        ->call('setAnnotationReader', [service('ibrows_associationresolver.annotation.reader')])
        ->call('setEntityManager', [service('doctrine.orm.entity_manager')])
        ->call('setResolverChain', [service('ibrows_associationresolver.resolverchain')]);

    $services->set('ibrows_associationresolver.resolverchain', ResolverChain::class);

    $services->set('ibrows_associationresolver.doctrine_annotations.reader', AnnotationReader::class);

    $services->set('ibrows_associationresolver.annotation.reader', AssociationAnnotationReader::class)
        ->call('setAnnotationReader', [service('ibrows_associationresolver.doctrine_annotations.reader')])
        ->call('setEntityManager', [service('doctrine.orm.entity_manager')]);

    foreach ([
        'ibrows_associationresolver.resolver.manytomany' => ManyToMany::class,
        'ibrows_associationresolver.resolver.manytoone'  => ManyToOne::class,
        'ibrows_associationresolver.resolver.onetoone'   => OneToOne::class,
        'ibrows_associationresolver.resolver.onetomany'  => OneToMany::class,
    ] as $id => $class) {
        $services->set($id, $class)
            ->arg(0, service('doctrine.orm.entity_manager'))
            ->tag('ibrows_associationresolver.resolverchain', ['priority' => -20])
            ->call('setSoftdeletable', ['%ibrows_associationresolver.softdelete%'])
            ->call('setSoftdeletableGetter', ['%ibrows_associationresolver.softdeletegetter%']);
    }
};
