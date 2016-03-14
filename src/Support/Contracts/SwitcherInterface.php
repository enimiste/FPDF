<?php
/**
 * Created by PhpStorm.
 * User: e.nouni
 * Date: 14/03/2016
 * Time: 13:21
 */

namespace Com\NickelIT\Support\Contracts;


interface SwitcherInterface
{

    /**
     * Mark a step
     *
     * @return void
     */
    function step();

    /**
     * Return true if we can alternate
     *
     * @return bool
     */
    function can();

    /**
     * Initialise the switcher
     *
     * @return void
     */
    function init();
}