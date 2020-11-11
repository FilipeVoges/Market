<?php


namespace App\Modules\Configuration;


use App\Entity;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View extends Entity
{
    /**
     * @var bool
     */
    protected $hasConn = false;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * View constructor.
     * @param string $template
     * @param array $vars
     */
    public function __construct(string $template, array $vars = []) {
        parent::__construct();

        $this->set('template', $template);

        if(!empty($vars)) {
            $this->set('vars', $vars);
        }

        $loader = new FilesystemLoader(APP_VIEWS_PATH);
        $twig = new Environment($loader, [
            'cache' => APP_CACHE_PATH . '\framework\views',
        ]);

        $this->set('twig', $twig);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function assign(string $key, $value) {
        $vars = $this->get('vars');
        $vars[$key] = $value;
        $this->set('vars', $vars);
    }

    /**
     * @return mixed
     */
    public function render() {
        $twig = $this->get('twig');
        $template = $this->get('template');
        $vars = $this->get('vars');

        return $twig->render($template, $vars);
    }
}