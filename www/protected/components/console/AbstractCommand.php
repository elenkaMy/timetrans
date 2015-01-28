<?php

/**
 * Yiic abstract command with common methods to use
 * @property-read ConsoleResponse $response
 */
class AbstractCommand extends CConsoleCommand
{

    /**
     * @var boolean
     */
    public $verbose = false;

    /**
     * @var boolean boolean whether to execute the migration in an interactive mode. Defaults to true.
     * Set this to false when performing migration in a cron job or background process.
     */
    public $interactive = true;

    public function prompt($message, $default = null) {
        if (!$this->interactive) {
            return $default;
        }
        return parent::prompt($message, $default);
    }

    public function confirm($message, $default = false)
    {
        if (!$this->interactive) {
            return true;
        }
        return parent::confirm($message, $default);
    }

    /**
     * get help with global options
     *
     * @see CConsoleCommand::getHelp()
     * @return string
     */
    public function getHelp()
    {
        $help = parent::getHelp();
        $global_options = $this->getGlobalOptions();
        if (!empty($global_options)) {
            $help .= PHP_EOL . 'Global options:';
            foreach($global_options as $name => $value) {
                foreach ($this->_getArrayRecursive((array)$value) as $subval)  {
                    $help .= PHP_EOL . '    [' . $name . '=' . $subval . ']';
                }
            }
        }
        return $help;
    }

    private function _getArrayRecursive(array $values)
    {
        $result = array();
        foreach ($values as $value) {
            $subvalues = is_array($value) ? $this->_getArrayRecursive($value) : array($value);
            $result = array_merge($result, $subvalues);
        }
        return $result;
    }

    /**
     * collect global options
     *
     * @return array
     */
    protected function getGlobalOptions()
    {
        $options = array();
        $refl = new ReflectionClass($this);
        $properties = $refl->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($properties as $property) {
            if ($property->getName() != 'defaultAction') {
                $options[$property->getName()] =  $property->getValue($this);
            }
        }
        return $options;
    }

    /**
     * show this information
     *
     * @return void
     */
    public function actionHelp()
    {
        $this->printf("Info: ".$this->getHelp());
    }

    /**
     * printf with line break
     *
     * @see printf()
     * @return void
     */
    public function printf()
    {
        $args = func_get_args(); // PHP 5.2 workaround
        call_user_func_array('printf', $args);
        printf(PHP_EOL);
    }

    /**
     * output text but only if verbose=true
     *
     * @see ClearcacheCommand::printf()
     * @return void
     */
    public function verbose()
    {
        if ($this->verbose) {
            $args = func_get_args(); // PHP 5.2 workaround
            call_user_func_array(array($this, 'printf'), $args);
        }
    }

    /**
     * Alias for Yii::app()->response.
     * @return ConsoleResponse
     */
    public function getResponse()
    {
        return Yii::app()->response;
    }
}
