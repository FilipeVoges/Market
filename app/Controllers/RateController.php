<?php


namespace App\Controllers;


use App\Modules\Configuration\View;
use App\Modules\Market\Rate;

class RateController extends Controller
{
    /**
     * @param array $messages
     * @return mixed
     */
    public function home(array $messages = []) {
        $view = new View('rates.twig');

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

        $value = (isset($this->request['rate'])) ? $this->request['rate'] : null;
        if(is_null($value)) {
            return $this->home(['error' => ['É necessário informar a taxa percentual.']]);
        }

        $rate = new Rate();

        $rate->set('name', $name);
        $rate->set('rate', $value);

        if($rate->save()){
            return $this->home(['success' => ['Taxa Cadastrada com Sucesso.']]);
        }
        return $this->home(['error' => ['Ocorreu um erro ao tentar registrar.']]);
    }

}