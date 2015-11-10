<?php

require('../../includes/SdmCore.php');
$sdmassembler = new SdmCore();
$sdmassembler->sdmCoreSdmReadArray($_POST['coredata']);
