<?php

namespace Adeliom\SyliusHappyCMSPlugin\Entity\SharedBlock;

use Adeliom\SyliusEasyCrudPlugin\Traits\EntityIdTrait;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\AbstractTranslation;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[HasLifecycleCallbacks]
#[MappedSuperclass]
class SharedBlockTranslation extends AbstractTranslation implements ResourceInterface, \Stringable
{
    use EntityIdTrait;

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
