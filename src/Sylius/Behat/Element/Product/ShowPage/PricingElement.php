<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Element\Product\ShowPage;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Element\Element;

final class PricingElement extends Element implements PricingElementInterface
{
    public function getPriceForChannel(string $channelName): string
    {
        $channelPriceRow = $this->getChannelPriceRow($channelName);

        if (null === $channelPriceRow) {
            return '';
        }

        $priceForChannel = $channelPriceRow->find('css', 'td:nth-child(2)');

        return $priceForChannel->getText();
    }

    public function getOriginalPriceForChannel(string $channelName): string
    {
        $channelPriceRow = $this->getChannelPriceRow($channelName);

        $priceForChannel = $channelPriceRow->find('css', 'td:nth-child(3)');

        return $priceForChannel->getText();
    }

    public function getCatalogPromotionsNamesForChannel(string $channelName): array
    {
        /** @var NodeElement[] $appliedPromotions */
        $appliedPromotions = $this->getAppliedPromotionsForChannel($channelName);

        return array_map(fn (NodeElement $element): string => $element->getText(), $appliedPromotions);
    }

    public function getCatalogPromotionLinksForChannel(string $channelName): array
    {
        $appliedPromotions = $this->getAppliedPromotionsForChannel($channelName);

        return array_map(fn (NodeElement $element): string => $element->getAttribute('href'), $appliedPromotions);
    }

    private function getAppliedPromotionsForChannel(string $channelName): array
    {
        /** @var NodeElement $channelPriceRow */
        $channelPriceRow = $this->getChannelPriceRow($channelName);

        return $channelPriceRow->findAll('css', '.applied-promotion');
    }

    private function getChannelPriceRow(string $channelName): ?NodeElement
    {
        return $this->getDocument()->find('css', sprintf('#pricing tr:contains("%s")', $channelName));
    }
}
