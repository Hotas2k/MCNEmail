<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Service;
use Doctrine\ORM\EntityManager,
    MCNEmail\Entity\Template as TemplateEntity;

class Template
{
    const SORT_EMPTY_TEMPLATE = 'empty_template';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \MCN\Object\Entity\Repository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('MCNEmail\Entity\Template');
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function getAvailableValidVariableKeys(array $array)
    {
        $keys = array();

        foreach ($array as $key => $value) {

            if (is_scalar($value)) {

                $keys[$key] = gettype($value);

            } else if($value instanceof \DateTime) {

                $keys[$key] = 'datetime';
            }

            if (is_object($value) && method_exists($value, 'toArray')) {

                $value = $value->toArray();
            }

            if (is_array($value)) {
                $keys[$key] = array_merge($keys, $this->getAvailableValidVariableKeys($value));
            }
        }

        return $keys;
    }


    /**
     * @param string $name
     * @return TemplateEntity|null
     */
    public function get($name)
    {
        $options = array(
            'parameters' => array(
                'name:eq'   => $name
            )
        );

        return $this->getRepository()
                    ->fetchOne($options);
    }

    /**
     * @param integer $id
     *
     * @return TemplateEntity
     */
    public function getById($id)
    {
        $options = array(
            'parameters' => array(
                'id:eq'   => $id
            )
        );

        return $this->getRepository()
                    ->fetchOne($options);
    }

    public function save(TemplateEntity $entity)
    {
        if (! $this->em->getUnitOfWork()->isInIdentityMap($entity)) {

            $this->em->persist($entity);
        }

        // TinyMCE removes html and body tags
        $entity->setTemplate(sprintf('<html><body>%s</body></html>', $entity->getTemplate()));

        $this->em->flush();
    }

    /**
     * @param string $name
     * @param string $description
     * @param array  $variables
     * @return void
     */
    public function create($name, $description, array $variables)
    {
        $variables = $this->getAvailableValidVariableKeys($variables);

        $entity = new TemplateEntity();
        $entity->fromArray(
            array(
                'name'        => $name,
                'variables'   => $variables,
                'description' => $description
            )
        );

        $this->em->persist($entity);
        $this->em->flush();
    }


    /**
     * @param array $options
     *
     * @return array|\MCN\Pagination\Pagination
     */
    public function fetchAll(array $options)
    {
        return $this->getRepository()
                    ->fetchAll($options);
    }
}
