<?php

declare(strict_types=1);

use Jerowork\PHPCleanLayers\Attribute\Test;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Guard\Layer\RootLevelClasses;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyBeAllowedBy;
use Jerowork\PHPCleanLayers\Guard\Rule\OnlyDependOn;

final class CleanArchitectureGuard
{
    #[Test]
    public function guardThatDomainLayerDoesNotDependOnOtherLayersNorVendor(): Guard
    {
        return Guard::layer('Domain')
            ->should(new OnlyDependOn(new RootLevelClasses()));
    }

    #[Test]
    public function guardThatOnlyApplicationLayerIsAllowedToUseDomainLayer(): Guard
    {
        return Guard::layer('Domain')
            ->should(new OnlyBeAllowedBy('Application'));
    }

    #[Test]
    public function guardThatApplicationLayerDoesOnlyDependOnDomainLayer(): Guard
    {
        return Guard::layer('Application')
            ->should(new OnlyDependOn(
                new RootLevelClasses(),
                'Domain',
            ));
    }

    #[Test]
    public function guardThatOnlyInfrastructureLayerIsAllowedToUseApplicationLayer(): Guard
    {
        return Guard::layer('Application')
            ->should(new OnlyBeAllowedBy('Infrastructure'));
    }
}
