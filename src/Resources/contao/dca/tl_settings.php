<?php
use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_settings']['fields']['mandanteninformationen_token'] = [
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'w50 clr'],
];

PaletteManipulator::create()
	->addLegend('mandanteninformationen_legend', 'news_categories_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('mandanteninformationen_token', 'mandanteninformationen_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings');