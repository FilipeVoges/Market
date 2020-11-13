<?php


namespace App\Controllers;

use App\Modules\Configuration\View;
use App\Modules\Market\Product;
use App\Modules\Market\Type;

/**
 * Class ProductController
 * @package App\Controllers
 */
class ProductController extends Controller
{
    /**
     * @param array $errors
     * @return mixed
     */
    public function home($messages = []) {
        $view = new View('products.twig');

        $types = Type::getAll();
        $view->assign('types', $types);
        $view->assign('messages', $messages);

        return $view->render();
    }

    public function register() {
        $name = (isset($this->request['name'])) ? $this->request['name'] : null;
        if(is_null($name)) {
            return $this->home(['error' => ['É necessário informar um nome.']]);
        }

        $type = (isset($this->request['type'])) ? $this->request['type'] : null;
        if(is_null($type)) {
            return $this->home(['error' => ['É necessário informar um Tipo.']]);
        }

        $price = (isset($this->request['price'])) ? $this->request['price'] : null;
        if(is_null($price)) {
            return $this->home(['error' => ['É necessário informar um Preço.']]);
        }

        $product = new Product();

        $product->set('name', $name);
        $product->set('price', $price);
        $product->set('type', $type);

        $product->save();

        return $this->home(['success' => ['Produto Cadastrado com Sucesso.']]);
    }
}