<?php

declare(strict_types=1);

namespace Kmergen\DoctrineTranslatable\Tests\HttpKernel;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Kmergen\DoctrineTranslatable\DoctrineTranslatableBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /**
     * @param list<string> $configs additional config files
     */
    public function __construct(
        private readonly array $configs = [],
    ) {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new DoctrineBundle();
        yield new DoctrineTranslatableBundle();
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/kmergen_doctrine_translatable/cache/' . md5(serialize($this->configs));
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/kmergen_doctrine_translatable/log';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../config/config_test.php');

        foreach ($this->configs as $config) {
            $loader->load($config);
        }
    }
}
