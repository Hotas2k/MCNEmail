<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNEmail\Repository;

/**
 * Class TemplateInterface
 * @package MCNEmail\Repository
 */
interface TemplateInterface
{
    /**
     * Check if the given template exists!
     *
     * @param string $id
     * @param string $locale
     *
     * @return bool
     */
    public function has($id, $locale);

    /**
     * Get a template
     *
     * @param string $id
     * @param string $locale
     *
     * @return \MCNEmail\Entity\Template|null
     */
    public function get($id, $locale);
}
