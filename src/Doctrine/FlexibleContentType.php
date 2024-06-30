<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Doctrine;

use Doctrine\DBAL\Types\JsonType;

class FlexibleContentType extends JsonType
{
    /**
     * @var string
     */
    public const ADELIOM_SYLIUS_CMS_FLEXIBLE_CONTENT_TYPE = 'adeliom_happy_cms_flexible_content_type';

    public function getName(): string
    {
        return self::ADELIOM_SYLIUS_CMS_FLEXIBLE_CONTENT_TYPE; // modify to match your constant name
    }
}
