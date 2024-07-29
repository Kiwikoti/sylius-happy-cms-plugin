<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Entity\SharedBlock;

use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Adeliom\SyliusEasyCrudPlugin\Traits\EntityNameTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_happy_cms__shared_block_translation')]
#[HasLifecycleCallbacks]
#[MappedSuperclass]
class SharedBlockTranslation extends AbstractTranslation implements ResourceInterface, \Stringable
{
    use EntityIdTrait;
    use EntityNameTrait;

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
