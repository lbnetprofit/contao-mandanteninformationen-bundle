<?php
namespace Netprofit\ContaoMandanteninformationenBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\CoreBundle\ContaoCoreBundle;
use Netprofit\ContaoMandanteninformationenBundle\ContaoMandanteninformationenBundle;

class Plugin implements BundlePluginInterface{

    public function getBundles(ParserInterface $parser): array{
        return [BundleConfig::create(ContaoMandanteninformationenBundle::class)->setLoadAfter([ContaoCoreBundle::class]), ];
    }

}