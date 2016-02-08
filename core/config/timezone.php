<?php

/**
 * This file configures the sites timezone. It sets the timezone for scripts and in the ini configuration to insure
 * correct timezone is used by all components.
 */

/* Set timezone for scripts. */
date_default_timezone_set('America/New_York');

/* Set timezone in the ini configuration. */
ini_set('date.timezone', 'America/New_York');
