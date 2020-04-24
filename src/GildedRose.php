<?php

namespace App;
final class GildedRose
{
    const EPIC_QUALITY = 50;
    const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    const BACKSTAGE_PASSES = 'Backstage passes to a TAFKAL80ETC concert';
    const AGED_BRIE = 'Aged Brie';
    const SELL_IN_ELEVEN = 11;
    const SELL_IN_SIX = 6;
    const SELL_IN_EXPIRATION = 0;
    const MINIMUM_QUALITY = 0;
    private $items = [];

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function updateQuality()
    {
        foreach ($this->items as $item) {
            if ($this->isDegradingItem($item)) {
                $this->decreaseQuality($item);
            }

            if ($this->isAgedBrie($item)) {
                $this->increaseQuality($item);
            }

            if ($this->isBackstagePasses($item)) {
                $this->increaseQuality($item);

                if ($this->isSellInLessThanElevent($item)) {
                    $this->increaseQuality($item);
                }
                if ($this->isSellInLessThanSix($item)) {
                    $this->increaseQuality($item);
                }
            }

            if (!$this->isSulfuras($item)) {
                $item->sell_in = $item->sell_in - 1;
            }

            if ($this->isAgedBrie($item) && $this->isSellInExpired($item)) {
                $this->increaseQuality($item);
            }
            if ($this->isDegradingItem($item) && $this->isSellInExpired($item)) {
                $this->decreaseQuality($item);
            }
            if ($this->isBackstagePasses($item) && $this->isSellInExpired($item)) {
                $this->makeItWorthless($item);
            }
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public function itemIsNotDegraded($item): bool
    {
        return $item->quality > self::MINIMUM_QUALITY;
    }

    /**
     * @param $item
     * @return bool
     */
    public function isSulfuras($item): bool
    {
        return $item->name == self::SULFURAS;
    }


    /**
     * @param $item
     * @return bool
     */
    private function isNotEpicQuality($item): bool
    {
        return $item->quality < self::EPIC_QUALITY;
    }

    /**
     * @param $item
     */
    private function decreaseQuality($item)
    {
        if ($this->itemIsNotDegraded($item)) {
            $item->quality = $item->quality - 1;
        }
    }

    /**
     * @param $item
     * @return bool
     */
    private function isSellInLessThanElevent($item): bool
    {
        return $item->sell_in < self::SELL_IN_ELEVEN;
    }

    /**
     * @param $item
     * @return bool
     */
    private function isSellInLessThanSix($item): bool
    {
        return $item->sell_in < self::SELL_IN_SIX;
    }

    /**
     * @param $item
     * @return bool
     */
    private function isSellInExpired($item): bool
    {
        return $item->sell_in < self::SELL_IN_EXPIRATION;
    }

    /**
     * @param $item
     */
    private function increaseQuality($item): void
    {
        if ($this->isNotEpicQuality($item)) {
            $item->quality = $item->quality + 1;
        }
    }

    private function isBackstagePasses($item): bool
    {
        return $item->name == self::BACKSTAGE_PASSES;
    }

    private function isDegradingItem($item): bool
    {
        return $item->name != self::AGED_BRIE && $item->name != self::BACKSTAGE_PASSES && !$this->isSulfuras($item);
    }

    /**
     * @param $item
     * @return bool
     */
    private function isAgedBrie($item): bool
    {
        return $item->name == self::AGED_BRIE;
    }

    /**
     * @param $item
     * @return int
     */
    private function makeItWorthless($item): int
    {
        return $item->quality = 0;
    }
}