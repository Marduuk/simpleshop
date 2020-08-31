<?php
declare(strict_types=1);

namespace App\DataFixtures\ORM\Loader;

use Faker\Generator as FakerGenerator;
use App\DataFixtures\ORM\Provider\DateTimeProvider;
use Nelmio\Alice\Loader\NativeLoader;

class CustomNativeLoader extends NativeLoader
{
    /**
     * {@inheritdoc}
     */
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = parent::createFakerGenerator();
        $generator->addProvider(new DateTimeProvider($generator));

        return $generator;
    }
}
