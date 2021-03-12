<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.log
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Joomla! System Logging Plugin.
 *
 * @since  1.5
 */
class PlgSystemTemplate_switch extends JPlugin
{

    protected $app;
    protected $document;



    /**
     * Change css paths in Template based on user state
     * return bool
     */

	public function onBeforeCompileHead()
	{
        $this->app      = Factory::getApplication();

        //var_dump(JMenu::getInstance('site')->getActive()->template_style_id);
        $this->document = Factory::getDocument();
        $params = $this->params;

        $pluginParams = [
            $params->get('theme')               =>  (int) $params->get('theme_id'),
            $params->get('themeAlternative')    =>  (int) $params->get('themeAlternative_id')
        ];

        $userThemeState = $this->app->getUserState( 'themeState' );

        if(empty($userThemeState))
        {
            $userThemeState = $params->get('theme');
        }

        //var_dump($userThemeState);
        //var_dump($pluginParams);


        if ($this->app->isSite())
        {
            $this->document->addScript(JURI::root() . 'plugins/system/template_switch/assets/js/switcher.js');
        }

        $stylesheets = $this->document->_styleSheets;
        //var_dump($stylesheets);
        $newStylesheets = [];

        foreach($stylesheets as $stylesheet => $value)
        {
			
            $stylesheetNew = $this->replaceStringBetween($stylesheet, 'yootheme_', '/', $userThemeState);

            foreach($pluginParams as $param => $themeId)
            {
                if($param === $userThemeState)
                {
                    $stylesheetNew = $this->replaceStringBetween($stylesheetNew, 'theme.', '.', $themeId);
                }
            }
            $newStylesheets[trim($stylesheetNew)] = $value;
        }
        if ($this->app->isSite())
        {
            $this->document->_styleSheets = $newStylesheets;
        }

      //var_dump($newStylesheets);
	}

    /**
     * Render the checkboxes / radio
     * return HTML
     */


	public function onAfterRender()
    {
        $app    = JFactory::getApplication();
        $stateVar = $app->getUserState( 'themeState' );
        $params = $this->params;

        if(empty($stateVar))
        {
            $stateVar = $params->get('theme');
        }

        $paramsTemplate = [
            'params'    =>  $params,
            'state'     =>  $stateVar
        ];

        $sHtml = $app->getBody();
        $params = $this->params;
        $lang = Factory::getLanguage();
        $lang->load('plg_system_template_switch', dirname(__FILE__));
        $layout = new JLayoutFile('checkboxes', JPATH_ROOT .'/plugins/system/template_switch/layouts');
        $html = $layout->render($paramsTemplate);

        $sHtml = str_replace('[templateSwitcher]', $html, $sHtml);

        $app->setBody($sHtml);
    }

    /**
     * Ajax Set Theme State for User
     * return bool
     */

    function onAjaxSetThemeState()
    {
        $app    = $this->app;
        $value  = $app->input->get('value', '', 'STRING');
        $app->setUserState( 'themeState', $value );
        return true;
    }

    /**
     * Replace the child theme string
     * return string
     */

    function replaceStringBetween($string, $start, $end, $toReplace){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return false;
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        $foundString = substr($string,$ini,$len);
        //var_dump($foundString);
        //var_dump($toReplace);
        $replacedString = str_replace($foundString, $toReplace, $string);
        return $replacedString;
    }

}
