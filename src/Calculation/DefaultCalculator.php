<?php

namespace Gloudemans\Shoppingcart\Calculation;

use Gloudemans\Shoppingcart\CartItem;
use Gloudemans\Shoppingcart\Contracts\Calculator;

class DefaultCalculator implements Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem)
    {
        $decimals = config('cart.format.decimals', 2);

        switch ($attribute) {
            case 'discount':
                //echo "#DISCOUNT=".$cartItem->getDiscountRate()->calculateDiscount($cartItem->price)."#";
                return $cartItem->getDiscount()->calculateDiscount($cartItem->price);
            case 'tax':
                return round($cartItem->priceTarget * ($cartItem->taxRate / 100), $decimals);
            case 'priceTax':
                return round($cartItem->taxedPrice(), $decimals);
            case 'discountTotal':
                //echo "#DISCOUNTTOTAL=" . $cartItem->discount ."*". $cartItem->qty."#";
                return round($cartItem->discount * $cartItem->qty, $decimals);
            case 'priceDiscount':
                return round($cartItem->discountedPrice(), $decimals);
            case 'priceTotal':
                return round($cartItem->price * $cartItem->qty, $decimals);
            case 'subtotal':
                //echo "#".$cartItem->priceTotal . " - " . $cartItem->discountTotal."#";
                return max(round($cartItem->priceTotal - $cartItem->discountTotal, $decimals), 0);
            case 'priceTarget':
                return round(($cartItem->priceTotal - $cartItem->discountTotal) / $cartItem->qty, $decimals);
            case 'taxTotal':
                return round($cartItem->subtotal * ($cartItem->taxRate / 100), $decimals);
            case 'total':
                return round($cartItem->subtotal + $cartItem->taxTotal, $decimals);
            default:
                return;
        }
    }
}
