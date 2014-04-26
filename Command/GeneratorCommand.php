<?php

namespace Ws\Bundle\GeneratorBundle\Command;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Ws\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Ws\Bundle\GeneratorBundle\Generator\Generator;

abstract class GeneratorCommand extends ContainerAwareCommand
{
    private $generator;

    // only useful for unit tests
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;
    }

    protected abstract function createGenerator();

    protected function getGenerator(BundleInterface $bundle = null)
    {
        if (null === $this->generator) {
            $this->generator = $this->createGenerator();
            $this->generator->setSkeletonDirs($this->getSkeletonDirs($bundle));
        }

        return $this->generator;
    }

    protected function getSkeletonDirs(BundleInterface $bundle = null)
    {
        $skeletonDirs = array();

        if (isset($bundle) && is_dir($dir = $bundle->getPath().'/Resources/WsGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        if (is_dir($dir = $this->getContainer()->get('kernel')->getRootdir().'/Resources/WsGeneratorBundle/skeleton')) {
            $skeletonDirs[] = $dir;
        }

        $skeletonDirs[] = __DIR__.'/../Resources/skeleton';
        $skeletonDirs[] = __DIR__.'/../Resources';

        return $skeletonDirs;
    }

    protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Ws\Bundle\GeneratorBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }
}
