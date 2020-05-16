<?php

/**
 * @copyright	Copyright (c) 2019 dw. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * system - webp Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	dw.webp
 */
class plgsystemwebp extends JPlugin
{

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array())
	{
		// call parent constructor
		parent::__construct($subject, $config);
	}

	public function onAfterInitialise()
	{
		JHtml::_('jquery.framework');
		JHtml::_('script', Juri::root().'plugins/system/webp/assets/js/webp.js', array('version' => 'auto', 'relative' => true));
		

		$app = JFactory::getApplication();

		if ($app->getName() != 'site') {
			return true;
		}

		$browserAgentName = $this->GetBrowserAgentName($_SERVER['HTTP_USER_AGENT']);
		if (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
			JFactory::getConfig()->set('webp', true);
		} elseif ($browserAgentName != 'Safari' && $browserAgentName != 'other') {
			JFactory::getConfig()->set('webp', true);
		} else {
			JFactory::getConfig()->set('webp', false);
			
		}
	}

	function onAfterRender()
	{
		$app = JFactory::getApplication();

		if ($app->getName() != 'site') {
			return true;
		}

		$body = JFactory::getApplication()->getBody();
		$body = $this->replaceSrcToWebp($body);
		JFactory::getApplication()->setBody($body);
		
		return true;
	}

	protected function GetBrowserAgentName($user_agent)
	{
		if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
		elseif (strpos($user_agent, 'Edge')) return 'Edge';
		elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
		elseif (strpos($user_agent, 'Safari')) return 'Safari';
		elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
		elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
		return 'Other';
	}

	protected function replaceSrcToWebp($body)
	{
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML(mb_convert_encoding($body,'HTML-ENTITIES', 'UTF-8'));
		libxml_use_internal_errors(false);
		$tags = $doc->getElementsByTagName('img');
		foreach ($tags as $tag) {
			$original_src = $tag->getAttribute('src');
			$imagePath = JPATH_ROOT . '/' . $original_src;
			$webpImage = $this->replaceImageExtension($original_src, 'webp');
			
			if(preg_match('/\.(jpg|png|jpeg)$/', $original_src)) {
				if( JFile::exists($webpImage) ){
					$tag->setAttribute('data-original', $original_src);
					$tag->setAttribute('src', $webpImage);
				} 
			} 
		}
		$body = $doc->saveHTML();
		return $body;
	}

	protected function replaceImageExtension($filename, $new_extension) {
    $info = pathinfo($filename);
    return $info['dirname']."/".$info['filename'] . '.' . $new_extension;
	}

	protected function convertToWebp($imagePath, $webpPath)
	{

	}
}
