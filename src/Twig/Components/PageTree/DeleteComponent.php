<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Twig\Components\PageTree;

use Sylius\TwigHooks\LiveComponent\HookableLiveComponentTrait;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

final class DeleteComponent
{
    use DefaultActionTrait;
    use HookableLiveComponentTrait;
//    use ComponentToolsTrait;

    public const OPEN_DELETE_MODAL_EVENT = 'happycms:page:open_delete_modal';

    #[ExposeInTemplate(name: 'page_id')]
    public string $pageId = '';

    public function __construct(
        protected readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

//    #[LiveAction]
//    public function delete(#[LiveArg] string $pageId): void
//    {
//        $this->pageId = $pageId;
//        $this->dispatchBrowserEvent(
//            self::OPEN_DELETE_MODAL_EVENT,
//            ['csrfToken' => $this->csrfTokenManager->getToken($pageId)->getValue()],
//        );
//    }
}
