<?php

/**
 * This file is part of the DBSessionStorage Module (https://github.com/Nitecon/DBSessionStorage.git)
 *
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon/DBSessionStorage.git)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 */

namespace DBSessionStorage;

use Laminas\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) : void {
        $storage = $e->getApplication()->getServiceManager()->get('DBSessionStorage\Storage\DBStorage');

        $storage->setSessionStorage();
    }//end of onBootstrap

    public function getConfig() {
       return include __DIR__ . '/config/module.config.php';
    }//end of getConfig

   /*
     No longer needed
     public function getAutoloaderConfig() : array {
        return ['Laminas\Loader\ClassMapAutoloader' => [__DIR__ . '/autoload_classmap.php']];
     }
   */

}//end of Module
