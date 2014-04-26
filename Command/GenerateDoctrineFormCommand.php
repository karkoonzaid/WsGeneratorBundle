<?php

namespace Ws\Bundle\GeneratorBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Ws\Bundle\GeneratorBundle\Generator\DoctrineFormGenerator;

class GenerateDoctrineFormCommand extends GenerateDoctrineCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('entity', InputArgument::REQUIRED, 'The entity class name to initialize (shortcut notation)'),
            ))
            ->setDescription('Generates a form type class based on a Doctrine entity')
            ->setHelp(<<<EOT
The <info>ws:generate:form</info> command generates a form class based on a Doctrine entity.

<info>php app/console ws:generate:form WsBlogBundle:Post</info>

Every generated file is based on a template. There are default templates but they can be overriden by placing custom templates in one of the following locations, by order of priority:

<info>BUNDLE_PATH/Resources/WsGeneratorBundle/skeleton/form
APP_PATH/Resources/WsGeneratorBundle/skeleton/form</info>

You can check https://github.com/web-solution/WsGeneratorBundle/tree/master/Resources/skeleton
in order to know the file structure of the skeleton
EOT
            )
            ->setName('ws:generate:form')
            ->setAliases(array('ck:generate:form'))
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = Validators::validateEntityName($input->getArgument('entity'));
        list($bundle, $entity) = $this->parseShortcutNotation($entity);

        $entityClass = $this->getContainer()->get('doctrine')->getAliasNamespace($bundle).'\\'.$entity;
        $metadata = $this->getEntityMetadata($entityClass);
        $bundle   = $this->getApplication()->getKernel()->getBundle($bundle);

        $generator = new DoctrineFormGenerator($this->getContainer()->get('filesystem'));
        $generator->setSkeletonDirs($this->getSkeletonDirs($bundle));
        $generator->generate($bundle, $entity, $metadata[0]);

        $output->writeln(sprintf(
            'The new %s.php class file has been created under %s.',
            $generator->getClassName(),
            $generator->getClassPath()
        ));
    }

    protected function createGenerator()
    {
        return new DoctrineFormGenerator($this->getContainer()->get('filesystem'));
    }
}
