<?php
/**
 *  <!--
 *  This file is part of the adventure php framework (APF) published under
 *  http://adventure-php-framework.org.
 *
 *  The APF is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The APF is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with the APF. If not, see http://www.gnu.org/licenses/lgpl-3.0.txt.
 *  -->
 */

/**
 * @package sites::vbc::pres::documentcontroller
 * @class vbc_controller
 *
 * Implements the document controller for the views 'view_one.html' and 'view_two.html'.
 *
 * @author Christian Achatz
 * @version
 * Version 0.1, 29.11.2008<br />
 */
class vbc_controller extends BaseDocumentController {

   /**
    * @public
    *
    * Fills the timestamp place holder.
    *
    * @author Christian Achatz
    * @version
    *  Version 0.1, 29.11.2008<br />
    */
   public function transformContent() {
      $this->setPlaceHolder('Timestamp', date('Y-m-d H:i:s'));
   }

}
