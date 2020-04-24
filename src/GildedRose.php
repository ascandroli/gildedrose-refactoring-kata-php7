<?php

namespace App;

final class GildedRose {

    const EPIC_QUALITY = 50;
    const SULFURAS = 'Sulfuras, Hand of Ragnaros';
    const BACKSTAGE_PASSES = 'Backstage passes to a TAFKAL80ETC concert';
    const AGED_BRIE = 'Aged Brie';
    const SELL_IN_ELEVEN = 11;
    const SELL_IN_SIX = 6;
    const SELL_IN_EXPIRATION = 0;
    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }

    public function updateQuality() {
        foreach ($this->items as $item) {
            if ($item->name != self::AGED_BRIE and $item->name != self::BACKSTAGE_PASSES) {
                if ($item->quality > 0) {
                    if ($item->name != self::SULFURAS) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < self::EPIC_QUALITY) {
                    $item->quality = $item->quality + 1;
                    if ($item->name == self::BACKSTAGE_PASSES) {
                        if ($item->sell_in < self::SELL_IN_ELEVEN) {
                            if ($item->quality < self::EPIC_QUALITY) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sell_in < self::SELL_IN_SIX) {
                            if ($item->quality < self::EPIC_QUALITY) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }
            
            if ($item->name != self::SULFURAS) {
                $item->sell_in = $item->sell_in - 1;
            }
            
            if ($item->sell_in < self::SELL_IN_EXPIRATION) {
                if ($item->name != self::AGED_BRIE) {
                    if ($item->name != self::BACKSTAGE_PASSES) {
                        if ($item->quality > 0) {
                            if ($item->name != self::SULFURAS) {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = 0;
                    }
                } else {
                    if ($item->quality < self::EPIC_QUALITY) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }
}

