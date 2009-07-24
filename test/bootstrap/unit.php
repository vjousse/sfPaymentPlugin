<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// load project configuration for specified $app
require_once dirname(__FILE__).'/../../../../config/ProjectConfiguration.class.php';
$configuration = new ProjectConfiguration();
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');