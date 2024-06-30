<?php

declare(strict_types=1);

namespace Adeliom\SyliusHappyCMSPlugin\Factory\CMS;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\TranslationInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;

interface CmsRoutableInterface extends RouteReferrersInterface
{
    /**
     * @return Collection|TranslationInterface[]
     *
     * @psalm-return Collection<array-key, TranslationInterface>
     */
    public function getTranslations(): Collection;

    public function isOnline(): bool;

    public function previewIsAvailable(): bool;

    public function isStatePublished(): bool;

    public function isStateUnpublished(): bool;

    public function isStatePending(): bool;

    public function hasState(?string $state): bool;

    public function isDatePublished(): bool;

    public function getRouteUnikName(): string;

    public function getRouteMethods(): array;

    public function getRouteOptions(TranslationInterface $translation): array;

    public function getRouteRequirements(TranslationInterface $translation): array;

    public function getRouteDefaults(TranslationInterface $translation): array;

    public function getRouteSchemes(TranslationInterface $translation): array;

    public function getRouteHost(TranslationInterface $translation): ?string;

    public function getRouteStaticPrefix(TranslationInterface $translation, bool $isPreview): string;

    public function getVariablePattern(TranslationInterface $translation, bool $isPreview): string;

    public function getRouteTemplate(): ?string;

    public function getRouteController(): ?string;

    public function getOnlineRoute(): ?RouteObjectInterface;

    public function getPreviewRoute(): ?RouteObjectInterface;
}
