<div align="center">

# Blocks

[Block definition](#flex-vs-shared-blocks) • [Generate block](#generate-flex-or-shared-blocks) • [Create block manually](#or-manually) • [Override default blocks](#override-default-blocks)

</div>

### Flex vs shared blocks

- Flex blocks are blocks that are created and managed directly within a page. They are unique to the page they are created on and cannot be reused across multiple pages.

- Shared blocks, on the other hand, are reusable blocks that can be created once and used across multiple pages. They are managed separately from pages and can be updated in one place to reflect changes across all pages that use them.


### Generate flex or shared blocks

```bash
php bin/console make:happy-cms:block
php bin/console make:happy-cms:block:shared
```

### Or manually

Create a file in your project at `src/Block/YourCustomBlock.php`

Flex blocks are automatically registered if they extend `Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock`
Shared blocks are automatically registered if they extend `Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractSharedBlockType`

```php
<?php

declare(strict_types=1);

namespace App\Block;

use Adeliom\SyliusHappyCMSPlugin\Factory\Block\AbstractBlock;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class YourCustomBlock extends AbstractBlock
{   
    /** Build your custom block form fields here, usefull to configure the content */
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'sylius_happy_cms.blocks.custom.fields.title',
            ]);
    }

    /** This name will be displayed in the block selection menu */
    public function getName(): string
    {
        return 'sylius_happy_cms.blocks.custom.name';
    }

    /** This tab allow you to group blocks in selection menu */
    public function getTab(): string
    {
        return 'sylius_happy_cms.blocks.tabs.text_blocks';
    }

    /** This icon will be displayed in the block selection menu */
    public function getIcon(): string
    {
        return '<img class="card-img-top" src="' . $this->getPackages()->getUrl('dist/placeholder-image.webp', AssetHappyCMSPackage::PACKAGE_NAME) . '" alt="">';
    }

    /** Path to your front-end template */
    public function getFrontEndTemplatePath(): string
    {
        return '@App/front/blocks/custom_block.html.twig';
    }
}
```

### Override default blocks

Few default blocks are provided with the plugin, you can override them by creating your own block class and registering it as a service.

Copy the default block from `vendor/adeliom/sylius-happy-cms-plugin/src/Block/` to your project `src/Block/` and modify it as you want.

Then, register your block into `config/services.yaml` :

```yaml
services:
 
    sylius.happy_cms.blocks.accordion_type:
        class: App\Blocks\AccordionBlockType
        public: true
        arguments:
            - '@doctrine.orm.entity_manager'
            - '@translator'
            - '@form.factory'
            - '@assets.packages'
        tags: [ 'sylius.happy_cms.block', 'form.type' ]

```
