<?php
/**
 * Created by PhpStorm.
 * User: e.nouni
 * Date: 14/03/2016
 * Time: 13:35
 */

namespace Com\NickelIT\Support;


use Com\NickelIT\Support\Contracts\SwitcherInterface;

class IntegerMultSwitcher implements SwitcherInterface
{
    /**
     * @var int
     */
    private $base;

    /**
     * IntegerMultSwitcher constructor.
     * @param int $base
     */
    public function __construct($base = 2)
    {
        $this->base = $base >= 2 ? $base : 2;
        $this->cursor = 1;
    }


    /**
     * Mark a step
     *
     * @return void
     */
    function step()
    {
        $this->cursor++;
    }

    /**
     * Return true if we can alternate
     *
     * @return bool
     */
    function can()
    {
        return $this->cursor > 0 && ($this->cursor % $this->base) == 0;
    }

    /**
     * Initialise the switcher
     *
     * @return void
     */
    function init()
    {
        $this->cursor = 1;
    }
}