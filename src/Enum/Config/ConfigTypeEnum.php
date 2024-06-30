<?php

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
class ConfigTypeEnum extends Enum
{
    private const CODE = 'code';
    private const EMAIL = 'email';
    private const NUMBER = 'number';
    private const JSON = 'json';
    private const TEXT = 'text';
    private const TEXTAREA = 'textarea';
    private const WYSIWYG = 'wysiwyg';
    private const BOOLEAN = 'boolean';
    private const IMAGE = 'image';
    private const FILE = 'file';
    private const COLOR = 'color';
    private const DATE = 'date';
    private const TIME = 'time';
    private const DATETIME = 'datetime';

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

        $field->setLabel('happy_cms.config.admin.type.' . $typeKey);
        $field->setDisabled(false);
        return $field;
    }
}
