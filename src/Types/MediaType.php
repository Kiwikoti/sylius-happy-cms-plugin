<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Types;

use Adeliom\SyliusHappyCMSPlugin\Entity\Media\Media;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MediaType extends Type
{

    private EntityManagerInterface $manager;
    private ParameterBagInterface $parameterBag;

    public function setManager(EntityManagerInterface $manager): void
    {
        $this->manager = $manager;
    }

    public function setParameterBag(ParameterBagInterface $parameterBag): void
    {
        $this->parameterBag = $parameterBag;
    }

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
            $class = $this->parameterBag->get('sylius_happy_cms.media.media_entity');
            if ($value && is_string($class) && class_exists($class)) {
                return $this->manager->getRepository($class)->find($value);
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
     * @inheritdoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
