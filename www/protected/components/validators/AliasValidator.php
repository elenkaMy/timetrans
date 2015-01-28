<?php

class AliasValidator extends CRegularExpressionValidator
{
    public $allowEmpty = false;

    public $pattern = '/^[a-zA-Z0-9_-]+$/';
}
