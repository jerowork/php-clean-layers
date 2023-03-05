<?php

declare(strict_types=1);

namespace Jerowork\PHPCleanLayers\Loader\Guard\ClassReflector;

use Jerowork\FileClassReflector\ClassReflectorFactory;
use Jerowork\PHPCleanLayers\Attribute\Test;
use Jerowork\PHPCleanLayers\Guard\Guard;
use Jerowork\PHPCleanLayers\Loader\Guard\DirectoryNotFoundException;
use Jerowork\PHPCleanLayers\Loader\Guard\GuardLoader;
use Jerowork\PHPCleanLayers\Loader\Guard\InvalidTestException;
use ReflectionNamedType;
use UnexpectedValueException;

final class ClassReflectorGuardLoader implements GuardLoader
{
    public function __construct(
        private readonly ClassReflectorFactory $classReflectorFactory,
    ) {
    }

    public function load(string $directory): array
    {
        try {
            $reflector = $this->classReflectorFactory
                ->create()
                ->addDirectory($directory);
        } catch (UnexpectedValueException $exception) {
            throw DirectoryNotFoundException::create($directory, $exception);
        }

        // Load if not registered in Composer
        foreach ($reflector->getFiles() as $file) {
            require $file;
        }

        $guards = [];

        foreach ($reflector->reflect()->getClasses() as $class) {
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
            }
        }

        return $guards;
    }
}
