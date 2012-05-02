<?php

class Model_Buildmenu_Redis extends Model_Buildmenu {

    protected $key_prefix = 'menu';
    protected $key_items = 'items';
    protected $key_products = 'products';

    public function fetchAll($menu) {
        if ($menu) {
            $key = $this->key_prefix.':'.$menu;
            $parent = $this->source->get("$key:parent");
        } else {
            $key = $this->key_prefix;
            $parent = null;
        }

        $items = $this->source->smembers("$key:{$this->key_items}");
        $returned_items = array();
        foreach ($items as $item) {
            $returned_items[$item] = $this->dict->getString($item);
        }

        $products = $this->source->smembers("$key:{$this->key_products}");
        $returned_products = array();
        foreach ($products as $product) {
            $returned_products[$product] = $this->dict->getString($product);
        }

        return array(
            'items' => $returned_items,
            'products' => $returned_products,
            'parent' => $parent
        );
    }

}

?>
