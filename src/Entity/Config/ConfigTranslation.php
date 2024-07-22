<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\Config;

use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Adeliom\SyliusHappyCMSPlugin\Enum\Config\ConfigTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Sylius\Component\Resource\Model\AbstractTranslation;

#[HasLifecycleCallbacks]
#[MappedSuperclass]
#[ORM\Entity]
#[ORM\Table(name: 'sylius_happy_cms__config_translation')]
class ConfigTranslation extends AbstractTranslation implements ConfigTranslationInterface, \Stringable
{
    use EntityIdTrait;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT, nullable: true)]
    private ?string $value = null;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    private function getType(): ?string
    {
        /** @var \Adeliom\SyliusHappyCMSPlugin\Entity\Config\Config $translatable */
        $translatable = $this->getTranslatable();
        if ($translatable->getType()) {
            return $translatable->getType();
        }

        return null;
    }

    public function __get($name)
    {
        if ($this->getType() == $name) {
            switch ($name) {
                case ConfigTypeEnum::DATE():
                    return $this->getDate();
                case ConfigTypeEnum::TIME():
                    return $this->getTime();
                case ConfigTypeEnum::DATETIME():
                    return $this->getDatetime();
                case ConfigTypeEnum::BOOLEAN():
                    return $this->getBoolean();
                default:
                    return $this->value;
            }
        }

        return null;
    }

    /**
     * @param null $value
     */
    public function __set($name, $value): void
    {
        if ($name == $this->getType()) {
            $this->value = $value;
        }
    }

    public function getBoolean()
    {
        if (ConfigTypeEnum::BOOLEAN() == $this->getType()) {
            return (bool) $this->value;
        }

        return null;
    }

    public function setDate(?\DateTime $date)
    {
        if (ConfigTypeEnum::DATE() == $this->getType() && $date) {
            $this->value = $date->format('Y-m-d');
        }

        return null;
    }

    public function getDate()
    {
        if (ConfigTypeEnum::DATE() == $this->getType()) {
            try {
                return new \DateTime($this->value);
            } catch (\Exception) {
                return null;
            }
        }

        return null;
    }

    public function setTime(?\DateTime $date)
    {
        if (ConfigTypeEnum::TIME() == $this->getType()) {
            $this->value = $date->format('H:i:s');
        }

        return null;
    }

    public function getTime()
    {
        if (ConfigTypeEnum::TIME() == $this->getType()) {
            try {
                return new \DateTime($this->value);
            } catch (\Exception) {
                return null;
            }
        }

        return null;
    }

    public function setDatetime(?\DateTime $date)
    {
        if (ConfigTypeEnum::DATETIME() == $this->getType() && $date) {
            $this->value = $date->format('Y-m-d H:i:s');
        }

        return null;
    }

    public function getDatetime()
    {
        if (ConfigTypeEnum::DATETIME() == $this->getType()) {
            try {
                return new \DateTime($this->value);
            } catch (\Exception) {
                return null;
            }
        }

        return null;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
