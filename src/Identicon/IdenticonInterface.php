<?php

/**
 * This file is part of the identicon package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Image\Color;

interface IdenticonInterface
{
    /**
     * @return Identity
     */
    public function getIdentity();

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @return Color
     */
    public function getBackgroundColor();

    /**
     * @return Color
     */
    public function getColor();

    /**
     * @return int
     */
    public function getWidth();

    /**
     * @return int
     */
    public function getHeight();
}
