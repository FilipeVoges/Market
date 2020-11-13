<?php


namespace App\Controllers;


use App\Modules\Configuration\View;
use App\Modules\Market\Rate;
use App\Modules\Market\Type;
use App\Modules\Market\TypeRate;

class TypeController extends Controller
{
    /**
     * @param array $messages
     * @return mixed
     */
    public function home(array $messages = []) {
        $view = new View('types.twig');

        $rates = Rate::getAll();
        $view->assign('rates', $rates);
        $view->assign('messages', $messages);

        return $view->render();
    }

    /**
     * @return mixed
     */
    public function register() {
        $name = (isset($this->request['name'])) ? $this->request['name'] : null;
        if(is_null($name)) {
            return $this->home(['error' => ['É necessário informar um nome.']]);
        }

        $rates = (isset($this->request['rates'])) ? $this->request['rates'] : null;
        if(is_null($rates)) {
            return $this->home(['error' => ['É necessário informar um Tipo.']]);
        }

        $type = new Type();

        $type->set('name', $name);
        $rs = $type->save();


        foreach ($rates as $rate) {
            $typeRate = new TypeRate();

            $typeRate->set('type', $type->get('id'));
            $typeRate->set('rate', $rate);

            $typeRate->save();
        }

        if($rs){
            return $this->home(['success' => ['Tipo de Produto Cadastrado com Sucesso.']]);
        }
        return $this->home(['error' => ['Ocorreu um erro ao cadastrar um Tipo de Produto.']]);

    }
}