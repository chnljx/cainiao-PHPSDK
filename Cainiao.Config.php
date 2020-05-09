<?php
require_once "lib/Cainiao.Config.Interface.php";

class CainiaoConfig extends CainiaoConfigInterface
{
    public function GetAppSecret()
    {
        if ($this->GetProductType()) {
            
        } else {
            return 'F53eqq903jQySV100Z8w06f9g914A13Z';
        }
    }

    public function GetLogisticProviderId()
    {
        if ($this->GetProductType()) {
            
        } else {
            return 'TmpFU1ZOUGoyRnoybDZmT3lyaW9hU3E4SDlobjdvMlJkemsxaGhHaVFMa2ZpMWtwOWsxSjFIUmMrUTlmNWdHVQ==';
        }
    }

    public function GetProductType()
    {
        return 0; // 1正式环境 0沙箱环境
    }
}
