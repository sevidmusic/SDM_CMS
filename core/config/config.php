<?php

/**
 * This file defines constants for the Sdm Cms core.
 *
 * THESE SETTINGS SHOULD ONLY BE CHANGED IF YOU KNOW EXACTLY WHAT YOUR DOING AND WHAT THE CONSEQUENCES ARE!
 * Additionally do not add anything to this file unless you are absolutely sure that you know what you are doing.
 *
 * Modifying this file in any way will have unknown consequences for your SDM CMS site and also may introduce
 * unknown security risks, lead to data loss, and could permanently break your site. BETTER TO LEAVE THIS FILE ALONE!
 */

/**
 * @constant string __SDM_ROOTDIR__ Site root directory.
 */
define('__SDM_ROOTDIR__', str_replace('/core/config', '', __DIR__));

/**
 * @constant string __SDM_INCDIR__ Site includes directory.
 */
define('__SDM_INCTDIR__', __SDM_ROOTDIR__ . '/core/includes');
