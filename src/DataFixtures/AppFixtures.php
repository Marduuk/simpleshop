<?php
declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\ORM\Loader\CustomNativeLoader;
use Locale;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    private const FIXTURE_FILES = [
        __DIR__ . '/ORM/Fixtures/products_fixtures.yaml',
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        Locale::setDefault('pl');

        $loader = new CustomNativeLoader();
        $objects = $loader->loadFiles(self::FIXTURE_FILES)->getObjects();

        foreach ($objects as $object) {
            $manager->persist($object);
        }
        $manager->flush();
    }
}
