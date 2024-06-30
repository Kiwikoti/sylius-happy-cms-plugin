<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Types;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\Media;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaType extends Type
{
    /**
     * @var string
     */
    public const MEDIATYPE = 'happy_cms_media_type';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        try {
            $listeners = $platform->getEventManager()->getListeners('getContainer');
            $listener = array_shift($listeners);
            /** @var ContainerInterface $container */
            $container = $listener->getContainer();
            $class = $container->getParameter('sylius_happy_cms.media.media_entity');

            if ($value) {
                return $container->get('doctrine.orm.entity_manager')->getRepository($class)->find($value);
            }

            return null;
        } catch (\Exception) {
            return null;
        }

    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value) {
            if ($value instanceof Media) {
                return $value->getId();
            }

            return $value;
        }

        return null;
    }

    public function getName(): string
    {
        return self::MEDIATYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
