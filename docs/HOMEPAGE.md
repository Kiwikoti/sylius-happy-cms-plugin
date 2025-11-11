- Override sylius home page to get the root cms page

```yaml
sylius_shop_homepage:
  path: /{_locale}/
  methods: [GET]
  controller: App\Controller\HomepageController::indexAction
```

- in App\Controller\HomepageController :

```php
public function indexAction(Request $request): Response
{
    $page = $this->manager
        ->getRepository(Page::class)
        ->getHomePage($request->getLocale());
    if (!is_null($page) && !is_null($page->getOnlineRoute())) {
        return $this->routeRenderService->renderAction($page, $request, $page->getOnlineRoute());
    } else {
        return new Response('', Response::HTTP_NOT_FOUND);
    }
}
```
