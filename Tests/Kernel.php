<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Tests;

use Mindy\Bundle\FormBundle\FormBundle;
use Mindy\Bundle\MailBundle\MailBundle;
use Mindy\Bundle\OrmBundle\OrmBundle;
use Mindy\Bundle\TemplateBundle\TemplateBundle;
use Mindy\Bundle\UserBundle\UserBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances
     */
    public function registerBundles()
    {
        return [
            new UserBundle(),
            new MailBundle(),
            new TemplateBundle(),
            new FormBundle(),
            new OrmBundle(),
            new SecurityBundle(),
            new FrameworkBundle(),
        ];
    }

    /**
     * Loads the container configuration.
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yaml');
    }
}
