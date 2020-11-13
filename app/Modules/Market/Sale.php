<?php


namespace App\Modules\Market;


use App\Entity;

/**
 * Class Sale
 * @package App\Modules\Market
 */
class Sale extends Entity
{
    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table = 'sales';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $status;

    /**
     * @return array
     * @throws \Exception
     */
    public function products() {
        $key = $this->get($this->get('key'));
        $productSale = ProductSale::getAll(['*'], ['sale' => $key]);

        $result = [];
        foreach ($productSale as $ps) {
            $product = Product::find($ps->get('product'), 'id', true);

            if(!$product){
                throw new \Exception('Produto não encontrado', 500);
            }

            $result[] = $product;
        }
        return $result;
    }
}