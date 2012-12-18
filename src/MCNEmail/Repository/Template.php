<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 */

/**
 * @namespace
 */
namespace MCNEmail\Repository;
use MCN\Object\QueryInfo,
    MCN\Object\Entity\Repository;

use MCNEmail\Service\Template as TemplateService;

class Template extends Repository
{
    protected function getBaseQuery(QueryInfo $qi)
    {
        $dqb = parent::getBaseQuery($qi);

        foreach($qi->getSort() as $field => $direction)
        {
            switch($field)
            {
                case TemplateService::SORT_EMPTY_TEMPLATE:
                    $dqb->addSelect('HIDDEN template.created_at = template.updated as template_empty')
                        ->addOrderBy('template_empty', $direction);
                    break;
            }
        }

        return $dqb;
    }
}
