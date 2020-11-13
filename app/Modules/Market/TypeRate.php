<?php


namespace App\Modules\Market;


use App\Entity;

/**
 * Class TypeRate
 * @package App\Modules\Market
 */
class TypeRate extends Entity
{
    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table = 'type_rate';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var int
     */
    protected $rate;
}