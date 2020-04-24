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
            if ($item->name != self::AGED_BRIE and $item->name != self::BACKSTAGE_PASSES) {
                if (!$this->isSulfuras($item)) {
                    $this->decreaseQuality($item);
                }
            } else {
                if ($this->isNotEpic($item)) {
                    $this->increaseQuality($item);
                    if ($item->name == self::BACKSTAGE_PASSES) {
                        if ($this->isSellInLessThanElevent($item)) {
                            $this->increaseQualityIfNotEpic($item);
                        }
                        if ($this->isSellInLessThanSix($item)) {
                            $this->increaseQualityIfNotEpic($item);
                        }
                    }
                }
            }

            if (!$this->isSulfuras($item)) {
                $item->sell_in = $item->sell_in - 1;
            }

            if ($this->isSellInExpired($item)) {
                if ($item->name === self::AGED_BRIE) {
                    $this->increaseQualityIfNotEpic($item);
                }

                if ($item->name != self::AGED_BRIE) {
                    if ($item->name != self::BACKSTAGE_PASSES) {
                        if ($this->isSulfuras($item) === false) {
                            $this->decreaseQuality($item);
                        }
                    }
                }

                if ($item->name === self::BACKSTAGE_PASSES) {
                    $item->quality = 0;
                }
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
     */
    public function increaseQuality($item): void
    {
        $item->quality = $item->quality + 1;
    }

    /**
     * @param $item
     * @return bool
     */
    private function isNotEpic($item): bool
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
    private function increaseQualityIfNotEpic($item): void
    {
        if ($this->isNotEpic($item)) {
            $this->increaseQuality($item);
        }
    }
}

