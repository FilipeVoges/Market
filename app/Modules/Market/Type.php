<?php


namespace App\Modules\Market;


use App\Entity;

/**
 * Class Type
 * @package App\Modules\Market
 */
class Type extends Entity
{
    /**
     * @var varchar $table;
     * @access protected
     */
    protected $table = 'types';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @return array
     * @throws \Exception
     */
    public function rates() {
        $key = $this->get($this->get('key'));
        $typeRate = TypeRate::getAll(['*'], ['type' => $key]);

        $result = [];
        foreach ($typeRate as $tr) {
            $rate = Rate::find($tr->get('rate'), 'id', true);

            if(!$rate){
                throw new \Exception('Taxa não encontrada', 500);
            }

            $result[] = $rate;
        }
        return $result;
    }

}