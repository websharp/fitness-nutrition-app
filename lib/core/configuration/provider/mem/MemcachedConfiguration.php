<?php
namespace APF\core\configuration\provider\mem;

/**
 * <!--
 * This file is part of the adventure php framework (APF) published under
 * http://adventure-php-framework.org.
 *
 * The APF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The APF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with the APF. If not, see http://www.gnu.org/licenses/lgpl-3.0.txt.
 * -->
 */
use APF\core\configuration\Configuration;
use APF\core\configuration\provider\BaseConfiguration;

/**
 * @package APF\core\configuration\provider\ini
 * @class MemcachedConfiguration
 *
 * Implements the configuration interface for the default APF scheme stored within
 * a memcached store.
 *
 * @author Christian Achatz
 * @version
 * Version 0.1, 30.10.2010<br />
 */
class MemcachedConfiguration extends BaseConfiguration implements Configuration {
}
