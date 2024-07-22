<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Config;

use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('key')]
#[ORM\HasLifecycleCallbacks]
#[ORM\MappedSuperclass(repositoryClass: \Adeliom\SyliusHappyCMSPlugin\Repository\Config\ConfigRepository::class)]
#[ORM\Entity]
#[ORM\Table(name: 'sylius_happy_cms__config')]
class Config implements ConfigInterface, TranslatableInterface
{
    use EntityIdTrait;
    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    #[ORM\Column(name: 'config', type: \Doctrine\DBAL\Types\Types::STRING, length: 255, unique: true)]
    private ?string $key = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::STRING, length: 255)]
    private ?string $type = null;

    public function __construct()
    {
        $this->initializeTranslationsCollection();
    }

    protected function createTranslation(): TranslationInterface
    {
        return new ConfigTranslation();
    }

    public function getTranslation(?string $locale = null): ConfigTranslation
    {
        /** @var ConfigTranslation $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    public static function getTranslationClass(): string
    {
        return ConfigTranslation::class;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return Config
     */
    public function setKey(mixed $key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Config
     */
    public function setName(mixed $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     *
     * @return Config
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Config
     */
    public function setType(mixed $type)
    {
        $this->type = $type;

        return $this;
    }
}
