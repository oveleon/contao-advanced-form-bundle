<?php
// Add fields to palette
use Contao\ArrayUtil;

$GLOBALS['TL_DCA']['tl_settings']['palettes'] = str_replace('{date_legend}', '{mapbox_legend},mapboxAccessToken;{date_legend}', $GLOBALS['TL_DCA']['tl_settings']['palettes']);

// Add fields
ArrayUtil::arrayInsert($GLOBALS['TL_DCA']['tl_settings']['fields'], 0, array
(
    'mapboxAccessToken' => array
    (
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['mapboxAccessToken'],
        'inputType'               => 'text',
        'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
    )
));