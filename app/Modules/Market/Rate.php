<?php


namespace App\Modules\Market;


use App\Entity;

/**
 * Class Rate
 * @package App\Modules\Market
 */
class Rate extends Entity
{
    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table = 'rates';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $rate;

    /**
     * @param float $price
     * @return float
     */
    public function calculate(float $price) : float {
        $rate = $this->get('rate')/100;
        return floatval($rate * $price);
    }

}