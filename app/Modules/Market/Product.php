<?php


namespace App\Modules\Market;


use App\Entity;

/**
 * Class Product
 * @package App\Modules\Market
 */
class Product extends Entity
{
    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table = 'products';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var int
     */
    protected $type;

    /**
     * @return Type
     * @throws \Exception
     */
    public function type() : Type {
        $type = Type::find($this->get('type'));

        if(!$type) {
            throw new \Exception('Tipo de Produto não Encontrado', 500);
        }

        return $type;
    }

    /**
     * @param int $sale
     * @return int
     */
    public function amount(int $sale) : int {
        $key = $this->get($this->get('key'));

        $productSale = ProductSale::getAll(['*'], ['sale' => $sale, 'product' => $key]);
        $ps = end($productSale);
        return $ps->get('amount');
    }

    public function total(int $sale) : float {
        $amount = $this->amount($sale);

        return floatval($amount * $this->get('price'));
    }

    public function totalWithRate(int $sale) {
        $total = $this->total($sale);

        $type = $this->type();

        $rates = $type->rates();

        $rateValue = 0.0;
        foreach ($rates as $rate) {
            $rateValue += $rate->calculate($this->get('price'));
        }

        return floatval($total + $rateValue);
    }
}