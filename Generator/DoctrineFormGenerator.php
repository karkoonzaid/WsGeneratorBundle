<?php

namespace Ws\Bundle\GeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class DoctrineFormGenerator extends Generator
{
    private $filesystem;
    private $actions;
    private $className;
    private $classPath;

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem A Filesystem instance
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getClassPath()
    {
        return $this->classPath;
    }

    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Generates the entity form class if it does not exist.
     *
     * @param BundleInterface   $bundle   The bundle in which to create the class
     * @param string            $entity   The entity relative class name
     * @param ClassMetadataInfo $metadata The entity metadata class
     */
    public function generate(BundleInterface $bundle, $entity, ClassMetadataInfo $metadata)
    {
        $parts       = explode('\\', $entity);
        $entityClass = array_pop($parts);

        $this->actions = array('Create', 'Edit');
        $this->className = $entityClass;
        $dirPath         = $bundle->getPath().'/Form';
        $this->classPath = $dirPath.'/'.str_replace('\\', '/', $entity);

        foreach($this->actions as $action){
            if (file_exists($this->classPath . $action .'.php')) {
                throw new \RuntimeException(sprintf('Unable to generate the %s form class as it already exists under the %s file', $this->className . $action, $this->classPath . $action . '.php'));
            }
        }

        if (count($metadata->identifier) > 1) {
            throw new \RuntimeException('The form generator does not support entity classes with multiple primary keys.');
        }

        $parts = explode('\\', $entity);
        array_pop($parts);

        foreach($this->actions as $action){
            $this->renderFile('form/FormType.php.twig', $this->classPath. $action .'.php', array(
                'fields'           => $this->getFieldsFromMetadata($metadata),
                'namespace'        => $bundle->getNamespace(),
                'entity_namespace' => implode('\\', $parts),
                'entity_class'     => $entityClass,
                'bundle'           => $bundle->getName(),
                'form_class'       => $this->className .''. $action,
                'form_type_name'   => strtolower(str_replace('\\', '_', $bundle->getNamespace()).($parts ? '_' : '').implode('_', $parts).'_'.substr($this->className.''. $action, 0)),
            ));
        }

    }

    /**
     * Returns an array of fields. Fields can be both column fields and
     * association fields.
     *
     * @param  ClassMetadataInfo $metadata
     * @return array             $fields
     */
    private function getFieldsFromMetadata(ClassMetadataInfo $metadata)
    {
        $fields = (array) $metadata->fieldNames;
        $fielsRemove = array('token', 'createdAt', 'updatedAt', 'slug');

        // Remove the primary key field if it's not managed manually
        if (!$metadata->isIdentifierNatural()) {
            $fields = array_diff($fields, $metadata->identifier);
        }

        // Remove the fieldsRemove if it's not managed manually
        foreach ($fielsRemove as $filed){
            if (isset($metadata->fieldNames[$filed])) {
                $fields = array_diff($fields, array($metadata->fieldNames[$filed]));
            }
        }

        foreach ($metadata->associationMappings as $fieldName => $relation) {
            if ($relation['type'] !== ClassMetadataInfo::ONE_TO_MANY) {
                $fields[] = $fieldName;
            }
        }

        return $fields;
    }
}
