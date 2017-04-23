<?php
namespace Wysiwyg\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Wysiwyg helper
 */
class WysiwygHelper extends Helper
{
	//public $helpers = ['Form'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
		'iframe' => [
			'class' => 'sw-editor'
		],
		'toolbar' => [
			'buttons' => [
				'bold',
				'code',
				'header',
				'italic',
				'link',
				'list',
				'media',
				'underline'
			],
			'class' => 'sw-toolbar'
		],
		'config' => [
			'media_url'
		]
	];

    public function init($editorConfig)
    {
		$config = $this->getConfig();
		$editor_class = $config['iframe']['class'];
		$buttons = $config['toolbar']['buttons'];
		$toolbar_class = $config['toolbar']['class'];
		$ret = '<div class="sw-wrap">';

		if (isset($editorConfig['toolbar']['buttons']))
			$buttons = $editorConfig['toolbar']['buttons'];

		if (isset($editorConfig['iframe']['class']))
			$editor_class = $editorConfig['iframe']['class'];

		$ret .= '<div class="' . $toolbar_class . '">';
		foreach ($buttons as $key => $button) {
			if (in_array($button, $config['toolbar']['buttons'])) {
				$ret .= $this->_getButton($button, $editorConfig['config']);
			}
		}
		$ret .= '</div>';
        $ret .= <<<EOT
<iframe name="wysiwygEditor" id="wysiwygEditor" class="$editor_class"></iframe>
</div>
<div class="sw-popup-wrap sw-is-hidden"><div class="sw-popup"></div></div>
EOT;
		return $ret;
    }

	public function sendToEditor()
	{
		return '<input type="button" value="' . __('Add') . '" id="wysiwyg_popup_add" class="sw-button sw-toolbar-button" data-function="addToEditor">';
	}

	private function _getButton($button, $config)
	{
		switch ($button) {
			case $button == 'italic':
				$function = 'iItalic';
				$label = '<span class="icon-italic" data-function="' . $function . '"></span>';
				break;
			case $button == 'bold':
				$function = 'iBold';
				$label = '<span class="icon-bold" data-function="' . $function . '"></span>';
				break;
			case $button == 'underline':
				$function = 'iUnderline';
				$label = '<span class="icon-underline" data-function="' . $function . '"></span>';
				break;
			case $button == 'code':
				$function = 'iCode';
				$label = '<span class="icon-embed" data-function="' . $function . '"></span>';
				break;
			case $button == 'media':
				$function = 'iMedia';
				$href = $config['media_url'];
				$label = '<span class="icon-image"></span>';
				$return = '<button class="sw-button sw-toolbar-button sw-toggle-popup" data-function="' . $function . '" data-arguments="' . $href . '">' . $label . '</button>';
				break;
			case $button == 'link':
				$label ='<span class="icon-link"></span>';
				$value = __('Add');
				$return = <<<EOT
<div class="sw-dropdown-wrap"><button class="sw-drop-menu-button sw-toolbar-button">$label</button>
<div class="sw-drop-menu-content sw-is-hidden">
	<input type="text" name="wysiwyg_link_label" id="wysiwyg_link_label" class="sw-selected-label">
	<input type="url" name="wysiwyg_link" id="wysiwyg_link">
	<input type="button" value="$value" class="sw-button" data-function="iLink">
</div></div>
EOT;
				break;
			case $button == 'list':
				$return = <<<EOT
<div class="sw-dropdown-wrap"><input type="button" value="List" class="sw-drop-menu-button sw-toolbar-button">
<div class="sw-drop-menu-content sw-is-hidden">
	<input type="button" value="Ordered" class="sw-button" data-function="iList" data-arguments="insertOrderedList">
	<input type="button" value="Unordered" class="sw-button" data-function="iList" data-arguments="insertUnorderedList">
</div></div>
EOT;
				break;
			case $button == 'header':
				$return = <<<EOT
<div class="sw-dropdown-wrap"><input type="button" value="H" class="sw-drop-menu-button sw-toolbar-button">
<div class="sw-drop-menu-content sw-is-hidden">
	<ul class="sw-ul">
		<li class="sw-col"><input type="button" value="H1" class="sw-button" data-function="iHeader" data-arguments="h1"></li>
		<li class="sw-col"><input type="button" value="H2" class="sw-button" data-function="iHeader" data-arguments="h2"></li>
		<li class="sw-col"><input type="button" value="H3" class="sw-button" data-function="iHeader" data-arguments="h3"></li>
		<li class="sw-col"><input type="button" value="H4" class="sw-button" data-function="iHeader" data-arguments="h4"></li>
		<li class="sw-col"><input type="button" value="H5" class="sw-button" data-function="iHeader" data-arguments="h5"></li>
		<li class="sw-col"><input type="button" value="H6" class="sw-button" data-function="iHeader" data-arguments="h6"></li>
	</ul>
</div></div>
EOT;
				break;
		}

		if (!isset($return)) {
			if (isset($function) && isset($label)) {
				$return = '<button class="sw-button sw-toolbar-button" data-function="' . $function . '">' . $label . '</button>';
			} else {
				return false;
			}
		}

		return $return;
	}
}
