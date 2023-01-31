<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        if ($this->environment == 'dev') {
            return '/var/cache';
        }
        
        return dirname(__DIR__).'/tmp/var/cache';
    }

    public function getLogDir(): string
    {
        if ($this->environment == 'dev') {
            return '/var/log';
        }
        
        return dirname(__DIR__).'/tmp/var/log';
    }
}
