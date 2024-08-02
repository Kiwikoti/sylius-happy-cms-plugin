<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Enum\Config;

use Adeliom\SyliusEasyCrudPlugin\Admin\Field\CodeEditorField;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\Field;
use Adeliom\SyliusEasyCrudPlugin\CrudFactory\Field\FieldInterface;
use Adeliom\SyliusEasyCrudPlugin\Helper\Enum;

/**
 * ConfigTypeEnum enum.
 *
 * @method static ConfigTypeEnum CODE()
 * @method static ConfigTypeEnum EMAIL()
 * @method static ConfigTypeEnum NUMBER()
 * @method static ConfigTypeEnum JSON()
 * @method static ConfigTypeEnum TEXT()
 * @method static ConfigTypeEnum TEXTAREA()
 * @method static ConfigTypeEnum WYSIWYG()
 * @method static ConfigTypeEnum BOOLEAN()
 * @method static ConfigTypeEnum IMAGE()
 * @method static ConfigTypeEnum FILE()
 * @method static ConfigTypeEnum COLOR()
 * @method static ConfigTypeEnum DATE()
 * @method static ConfigTypeEnum TIME()
 * @method static ConfigTypeEnum DATETIME()
 */
final class ConfigTypeEnum extends Enum
{
    public const CODE = 'code';

    public const EMAIL = 'email';

    public const NUMBER = 'number';

    public const JSON = 'json';

    public const TEXT = 'text';

    public const TEXTAREA = 'textarea';

    public const WYSIWYG = 'wysiwyg';

    public const BOOLEAN = 'boolean';

    public const IMAGE = 'image';

    public const FILE = 'file';

    public const COLOR = 'color';

    public const DATE = 'date';

    public const TIME = 'time';

    public const DATETIME = 'datetime';

    public static function getAdminField(string $typeKey): FieldInterface
    {
        $field = Field::new('value');
        if (
            $typeKey === self::CODE ||
            $typeKey === self::JSON
        ) {
            $field = CodeEditorField::new('value')
                ->setLanguage('json');
        }

        $field->setLabel('sylius_happy_cms.config.admin.type.' . $typeKey);
        $field->setDisabled(false);

        return $field;
    }
}
