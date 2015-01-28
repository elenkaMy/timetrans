<?php

/**
 * Class Favourites
 * @property-read Product[] $favouritesProducts in id => Product format.
 */
class Favourites extends CApplicationComponent
{
    const PRODUCTS_PARAM = 'products';

    /**
     * @var array()|Product[]
     */
    private $_products;

    /**
     * @param Product $product
     * @return $this Favourites
     */
    public function addFavouritesProduct(Product $product)
    {
        $favouritesData = $this->getSessionFavouritesData();
        $favouritesData[self::PRODUCTS_PARAM][$product->id] = $product;
        $this->setSessionFavouritesData($favouritesData);

        if (isset($this->_products)) {
            $this->_products[$product->id] = $product;
        }
        return $this;
    }
    
    /**
     * @param Product $product
     * Delete the user's product from favourites
     * @return $this Favourites
     */
    public function deleteFavouritesProduct(Product $product)
    {
        $favouritesData = $this->getSessionFavouritesData();
        if (isset($favouritesData[self::PRODUCTS_PARAM][$product->id])) {
            unset($favouritesData[self::PRODUCTS_PARAM][$product->id]);
        }
        if (isset($this->_products[$product->id])) {
            unset($this->_products[$product->id]);
        }
        $this->setSessionFavouritesData($favouritesData);
        return $this;
    }

    /**
     * @return array
     */
    protected function getSessionFavouritesData()
    {
        $favouritesData = Yii::app()->session[self::PRODUCTS_PARAM];
        if (empty($favouritesData)) {
            return array();
        }
        return $favouritesData;
    }

    /**
     * @param array $favouritesData
     * @return $this Favourites
     */
    protected function setSessionFavouritesData(array $favouritesData = null)
    {
        Yii::app()->session[self::PRODUCTS_PARAM] = $favouritesData;
        return $this;
    }

    /**
     * @return array()|array Product[] in id => Product format.
     */
    public function getFavouritesProducts()
    {
        if ($this->_products !== null) {
            return $this->_products;
        }
        $favouritesData = $this->getSessionFavouritesData();
        if (!empty($favouritesData[self::PRODUCTS_PARAM])) {
            return $favouritesData[self::PRODUCTS_PARAM];
        }
        return array();
    }
}
