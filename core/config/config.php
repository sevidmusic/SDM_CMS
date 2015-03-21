<?php

/** THESE SETTINGS SHOULD ONLY BE CHANGED IF YOU KNOW EXACTLY WHAT YOUR DOING AND WHAT THE CONSEQUENCES ARE! */
/** Additionally do not add any constants to this file unless you are absolutely sure that you know what you are doing */
/**
 * Modifying this file in any way will have unknown consequences for your SDM CMS site and also may introduce
 * unknown security risks, lead to data loss, and possibly permantly break your site. BETTER TO LEAVE THIS ALONE!
 */
////////////////////////
//  SITE CONSTANTS ///
////////////////////////

/**
 * CONSTANT for the sites Root directory
 */
define('__SDM_ROOTDIR__', str_replace('/core/config', '', __DIR__));

/**
 * CONSTANT for the sites Includes directory
 */
define('__SDM_INCTDIR__', __SDM_ROOTDIR__ . '/core/includes');
