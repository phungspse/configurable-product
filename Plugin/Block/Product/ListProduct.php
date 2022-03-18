<?php

namespace PhungSpse\ConfigurableProduct\Plugin\Block\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProviderInterface;
use Magento\Catalog\Block\Product\ListProduct as Subject;

class ListProduct
{
    /** @var LowestPriceOptionsProviderInterface */
    protected $lowestPriceOptionsProvider;

    /**
     * ConfigurablePrice constructor.
     * @param LowestPriceOptionsProviderInterface $lowestPriceOptionsProvider
     */
    public function __construct(
        LowestPriceOptionsProviderInterface $lowestPriceOptionsProvider
    ){
        $this->lowestPriceOptionsProvider = $lowestPriceOptionsProvider;
    }

    /**
     * @param Subject $subject
     * @param Product $product
     * @return array
     */
    public function beforeGetProductPrice(Subject $subject, Product $product)
    {
        $product = $this->getChildProductLowestPrice($product);
        return [$product];
    }

    /**
     * @param ProductInterface $parentProduct
     * @return ProductInterface
     */
    protected function getChildProductLowestPrice($parentProduct)
    {
        if ($parentProduct->getTypeId() != 'simple') {
            $products = $this->lowestPriceOptionsProvider->getProducts($parentProduct);
            if (!empty($products)) {
                return reset($products);
            }
        }

        return $parentProduct;
    }
}