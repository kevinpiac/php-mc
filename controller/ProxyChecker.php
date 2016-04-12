<?php

class ProxyChecker extends Controller
{
    
	public function checkProxys()
    {
        $this->loadModel('Proxy');
        $this->Proxy->removeExpiredProxys();
        $this->Proxy->restartProxys();
    }
}