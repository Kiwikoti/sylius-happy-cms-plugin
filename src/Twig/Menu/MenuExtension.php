<?php

namespace Adeliom\SyliusHappyCMSPlugin\Twig\Menu;

use Adeliom\SyliusHappyCMSPlugin\Exceptions\Menu\MenuNotFoundException;
use Adeliom\SyliusHappyCMSPlugin\Exceptions\Menu\TemplateNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    /*
    * @param \Doctrine\ORM\EntityRepository $menuClass
    */
   public function __construct(
       /**
        * @readonly
        */
        private Environment $twig,
        /**
         * @readonly
         */
        private EntityManagerInterface $em,
        /**
         * @readonly
         */
        private string $menuClass,
        /**
         * @readonly
         */
        private string $menuItemClass
    ) {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('happy_cms_menu', \Closure::fromCallable(fn (Environment $env, array $context, $code, array $extra = []): Markup => $this->renderMenu($env, $context, $code, $extra)), ['is_safe' => ['js', 'html'], 'needs_context' => true, 'needs_environment' => true]),
        ];
    }

    /**
     * @param $code
     * @param array $extra
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderMenu(Environment $env, array $context, $code, $extra = []): Markup
    {
        $menu = $this->em->getRepository($this->menuClass)->findOneByCode($code);

        if (empty($menu)) {
            throw new MenuNotFoundException($code);
        }

        $template = '@SyliusHappyCMSPlugin/front/menus/'.$code.'.html.twig';

        if (!empty($extra['template'])) {
            $template = $extra['template'];
        }

        if (!$this->twig->getLoader()->exists($template)) {
            throw new TemplateNotFoundException($template);
        }

        $rootItem = $this->em->getRepository($this->menuItemClass)->findOneBy([
            'menu' => $menu,
            'parent' => null,
        ]);

        $menu->setRootItem($rootItem);

        return new Markup($this->twig->render($template, array_merge($context, [
            'menu' => $menu,
        ], $extra)), 'UTF-8');
    }
}
