<?php

/**
 * This file is part of PHPRedmin project.
 *
 * (c) Sasan Rose <sasan.rose@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpRedmin\Integration\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * This extension is used to load global variables into twig templates.
 */
class GlobalVars extends AbstractExtension implements GlobalsInterface
{
    /**
     * A protected variable to store global vars.
     *
     * @var array
     */
    protected $globals = [];

    /**
     * Creates a GlobalVars instance.
     *
     * @param array $globals
     */
    public function __construct(array $globals)
    {
        $this->globals = $globals;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return $this->globals;
    }
}
