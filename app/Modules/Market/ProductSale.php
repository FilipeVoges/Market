<?php


namespace App\Modules\Market;


use App\Entity;

/**
 * Class ProductSale
 * @package App\Modules\Market
 */
class ProductSale extends Entity
{
    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table = 'product_sale';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $product;

    /**
     * @var int
     */
    protected $sale;

    /**
     * @var int
     */
    protected $amount;

}