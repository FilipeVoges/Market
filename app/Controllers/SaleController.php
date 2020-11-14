<?php


namespace App\Controllers;


use App\Modules\Configuration\View;
use App\Modules\Market\Product;
use App\Modules\Market\ProductSale;
use App\Modules\Market\Sale;

class SaleController extends Controller
{
    /**
     * @param array $messages
     * @return mixed
     */
    public function home(array $messages = []) {
        $view = new View('sales.twig');

        $sales = Sale::getAll();
        $view->assign('sales', $sales);
        $view->assign('messages', $messages);

        return $view->render();
    }

    /**
     * @return mixed
     */
    public function register() {

        $sale = new Sale();
        $sale->set('status', 1);

        if($sale->save(true)){
            return $this->home(['success' => ['Venda Cadastrada com Sucesso.']]);
        }
        return $this->home(['error' => ['Ocorreu um erro ao cadastrar a venda']]);
    }

    /**
     * @param int|null $id
     * @return mixed
     * @throws \Exception
     */
    public function sale(int $id = null) {
        if(is_null($id)) {
            return $this->home(['error' => ['Venda não encontrada']]);
        }

        $view = new View('sale.twig');

        $sale = Sale::find($id);

        if(!$sale){
            return $this->home(['error' => ['Venda não encontrada']]);
        }

        $products = $sale->products();
        $view->assign('products', $products);
        $view->assign('sale', $id);

        return $view->render();
    }

    /**
     * @param int|null $id
     * @return mixed
     */
    public function addProduct(int $id = null)
    {
        if (is_null($id)) {
            return $this->home(['error' => ['Venda não encontrada']]);
        }

        $view = new View('productsale.twig');

        $products = Product::getAll();
        $view->assign('products', $products);
        $view->assign('sale', $id);

        return $view->render();
    }

    public function registerProduct() {
        if(empty($this->request)){
            redirect('sales');
            return;
        }
        $product = (isset($this->request['product'])) ? $this->request['product'] : null;
        if(is_null($product)) {
            return $this->home(['error' => ['É necessário informar qual produto está sendo vendido.']]);
        }

        $sale = (isset($this->request['sale'])) ? $this->request['sale'] : null;
        if(is_null($sale)) {
            return $this->home(['error' => ['É necessário informar a Venda.']]);
        }

        $amount = (isset($this->request['amount'])) ? $this->request['amount'] : null;
        if(is_null($amount)) {
            return $this->home(['error' => ['É necessário informar a quantidade.']]);
        }

        $ps = new ProductSale();

        $ps->set('product', $product);
        $ps->set('sale', $sale);
        $ps->set('amount', $amount);

        if($ps->save()){
            return $this->sale($sale);
        }
        return $this->home($sale);
    }

}