<?php
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Netprofit\ContaoMandanteninformationenBundle\Controller\FrontendModule\MandanteninformationenController;

$GLOBALS['TL_DCA']['tl_module']['palettes'][MandanteninformationenController::TYPE] = '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage;{template_legend},customTpl';