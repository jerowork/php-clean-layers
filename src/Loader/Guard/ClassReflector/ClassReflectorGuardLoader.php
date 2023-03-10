<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Guard\ClassReflector;

use Jerowork\FileClassReflector\ClassReflectorFactory;
use Jerowork\PHPCleanLayers\Attribute\Test;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Loader\Guard\GuardLoader;
use Jerowork\PHPCleanLayers\Loader\Guard\InvalidTestException;
use ReflectionNamedType;

final class ClassReflectorGuardLoader implements GuardLoader
{
    public function __construct(
        private readonly ClassReflectorFactory $classReflectorFactory,
    ) {
    }

    public function load(string ...$paths): array
    {
        $reflector = $this->classReflectorFactory
            ->create()
            ->addFile(...array_filter($paths, fn ($path) => is_file($path)))
            ->addDirectory(...array_filter($paths, fn ($path) => is_dir($path)));

        // Load if not registered in Composer
        foreach ($reflector->getFiles() as $file) {
            require_once $file;
        }

        $guards = [];
        $processedClasses = [];
        foreach ($reflector->reflect()->getClasses() as $class) {
            if (in_array($class->name, $processedClasses, true)) {
                continue;
            }

            $className = $class->name;
            $guardClass = new $className();

            foreach ($class->getMethods() as $method) {
                if (count($method->getAttributes(Test::class)) === 0) {
                    continue;
                }

                if ($method->getReturnType() instanceof ReflectionNamedType
                    && $method->getReturnType()->getName() !== Guard::class
                ) {
                    throw InvalidTestException::doesNotReturnGuard(
                        sprintf('%s::%s', $className, $method->getName()),
                    );
                }

                $callable = [$guardClass, $method->name];

                if (!is_callable($callable)) {
                    continue;
                }

                $guards[] = $callable();
                $processedClasses[] = $class->name;
            }
        }

        return $guards;
    }
}
