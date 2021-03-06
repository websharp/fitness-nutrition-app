<?php
/**
 * <!--
 * This file is part of the wsCatalyst-Extensions (wsC) for the
 * Adventure-PHP-Framework published under
 * https://sourceforge.net/projects/wscatalyst.
 *
 * The wsC is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The wsC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with the wsC. If not, see http://www.gnu.org/licenses/lgpl-3.0.txt.
 * -->
 */

/*
 * @file Renamer.php
 * @namespace WSC\external\compiler
 *
 *
 * @author Florian Horn
 * @version
 * Version 1.0, 21.10.2010<br/>
 * Version 1.1, 26.02.2011<br/>
 * Version 1.2, 25.08.2011<br/>
 * Version 2.0, 03.11.2013<br/>
 *
 */

namespace WSC\external\compiler;

use WSC\external\compiler\AbstractBuilder;
use APF\core\configuration\provider\BaseConfiguration;

class Renamer extends AbstractBuilder
{
    /**
     * Constructor
     * @param BaseConfiguration $oConfig 
     */
    public function __construct(BaseConfiguration $oAppConfig, $sAppName)
    {
        parent::__construct($oAppConfig,$sAppName);
        $this->startRenaming();
    }



    /**
     * Start the renaming process
     */
    public function startRenaming()
    {
        foreach ($this->__aRename as $Project => $R) {
            foreach ($R as $dirR) {
                rename($dirR["Old"], $dirR["New"]);
            }
        }
    }
}
