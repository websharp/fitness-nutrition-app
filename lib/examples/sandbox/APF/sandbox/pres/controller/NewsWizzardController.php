<?php
namespace APF\sandbox\pres\controller;

use APF\core\configuration\ConfigurationException;
use APF\core\configuration\provider\ini\IniConfiguration;
use APF\core\database\AbstractDatabaseHandler;
use APF\core\database\ConnectionManager;
use APF\core\pagecontroller\BaseDocumentController;
use APF\modules\genericormapper\data\tools\GenericORMapperManagementTool;
use APF\tools\http\HeaderManager;

class NewsWizzardController extends BaseDocumentController {

   private static $CONFIG_SECTION_NAME = 'Sandbox-News';
   private static $CONFIG_SUB_SECTION_NAME = 'DB';

   public function transformContent() {

      // step 1: create database config file
      $formNewConfig = & $this->getForm('new-db-config');

      if ($formNewConfig->isSent() && $formNewConfig->isValid()) {

         // rerieve the form values
         $host = $formNewConfig->getFormElementByName('db-host')->getAttribute('value');
         $port = $formNewConfig->getFormElementByName('db-port')->getAttribute('value');
         $user = $formNewConfig->getFormElementByName('db-user')->getAttribute('value');
         $pass = $formNewConfig->getFormElementByName('db-pass')->getAttribute('value');
         $name = $formNewConfig->getFormElementByName('db-name')->getAttribute('value');

         // create configuration and save it!
         $dbSection = new IniConfiguration();

         $section = new IniConfiguration();
         $section->setValue('Host', $host);
         $section->setValue('User', $user);
         $section->setValue('Pass', $pass);
         $section->setValue('Name', $name);
         $section->setValue('Port', $port);
         $section->setValue('Collation', 'utf8_general_ci');
         $section->setValue('Charset', 'utf8');
         $section->setValue('Type', 'APF\core\database\MySQLiHandler');

         $dbSection->setSection(self::$CONFIG_SUB_SECTION_NAME, $section);

         // load existing configuration or create new one
         try {
            $config = $this->getConfiguration('APF\core\database', 'connections.ini');
         } catch (ConfigurationException $e) {
            $config = new IniConfiguration();
         }

         $config->setSection(self::$CONFIG_SECTION_NAME, $dbSection);
         $this->saveConfiguration('APF\core\database', 'connections.ini', $config);

         HeaderManager::forward('./?page=news-wizzard#step-2');
         return;
      }

      $configAvailable = false;
      $subSection = null;
      try {
         $config = $this->getConfiguration('APF\core\database', 'connections.ini');
         $tmpl = & $this->getTemplate('db-config-exists');

         $section = $config->getSection(self::$CONFIG_SECTION_NAME);
         if ($section == null) {
            throw new ConfigurationException('Section "' . self::$CONFIG_SECTION_NAME
                  . '" is not contained in the current configuration!', E_USER_ERROR);
         }
         $subSection = $section->getSection(self::$CONFIG_SUB_SECTION_NAME);

         $rawHost = $subSection->getValue('Host');
         $colon = strpos($rawHost, ':');
         if($colon) {
             $host = substr($rawHost, 0, $colon);
             $port = substr($rawHost, $colon + 1);
         } else {
             $host = $subSection->getValue('Host');
             $port = $subSection->getValue('Port');
         }


         $tmpl->setPlaceHolder('host', $host);
         $tmpl->setPlaceHolder('port', $port);
         $tmpl->setPlaceHolder('user', $subSection->getValue('User'));
         $tmpl->setPlaceHolder('pass', $subSection->getValue('Pass'));
         $tmpl->setPlaceHolder('name', $subSection->getValue('Name'));
         $tmpl->setPlaceHolder('collation', $subSection->getValue('Collation'));
         $tmpl->setPlaceHolder('charset', $subSection->getValue('Charset'));
         $tmpl->setPlaceHolder('type', $subSection->getValue('Type'));

         $tmpl->transformOnPlace();

         $configAvailable = true;
      } catch (ConfigurationException $e) {
         $formNewConfig->transformOnPlace();
      }

      // step 2: create database layout
      $databaseLayoutInitialized = false;
      if ($configAvailable) {

         $formInitDb = & $this->getForm('init-db');
         try {
            /* @var $connMgr ConnectionManager */
            $connMgr = $this->getServiceObject('APF\core\database\ConnectionManager');
            /* @var $conn AbstractDatabaseHandler */
            $conn = $connMgr->getConnection(self::$CONFIG_SECTION_NAME);

            // check for db layout...
            $result = $conn->executeTextStatement('SHOW TABLES');
            $setupDone = false;
            $offsetName = 'Tables_in_' . $subSection->getValue('Name');
            while ($data = $conn->fetchData($result)) {
               if ($data[$offsetName] == 'ent_news') {
                  $setupDone = true;
               }
            }

            if ($setupDone) {
               $this->getTemplate('tables-exist')->transformOnPlace();
               $databaseLayoutInitialized = true;
            } else {
               if ($formInitDb->isSent()) {

                  // setup database layout
                  /* @var $setup GenericORMapperManagementTool */
                  $setup = & $this->getServiceObject('APF\modules\genericormapper\data\tools\GenericORMapperManagementTool');
                  $setup->addMappingConfiguration('APF\extensions\news', 'news');
                  $setup->addRelationConfiguration('APF\extensions\news', 'news');
                  $setup->setConnectionName(self::$CONFIG_SECTION_NAME);
                  $setup->run();

                  HeaderManager::forward('?page=news-wizzard#step-3');
               } else {
                  $formInitDb->transformOnPlace();
               }
            }
         } catch (\Exception $e) {
            $tmplDbConnErr = & $this->getTemplate('db-conn-error');
            $tmplDbConnErr->setPlaceHolder('exception', $e->getMessage());
            $tmplDbConnErr->transformOnPlace();
         }
      } else {
         $this->getTemplate('step-1-req')->transformOnPlace();
      }

      // step 3: call news backend
      if ($databaseLayoutInitialized) {
         $this->getTemplate('step-3')->transformOnPlace();
      } else {
         $this->getTemplate('step-2-req')->transformOnPlace();
      }
   }

}
