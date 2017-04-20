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
			'class' => 'wysiwyg-editor'
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
			'class' => 'wysiwyg-toolbar'
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
		$ret = "";

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
<div class="wysiwyg-popup"></div>
EOT;
		return $ret;
    }

	public function sendToEditor()
	{
		return '<input type="button" value="' . __('Add') . '" id="wysiwyg_popup_add" class="wysiwyg-button wysiwyg-toolbar-button" data-function="addToEditor">';
	}

	private function _getButton($button, $config)
	{
		switch ($button) {
			case $button == 'italic':
				$function = 'iItalic';
				$label = 'I';
				break;
			case $button == 'bold':
				$function = 'iBold';
				$label = 'B';
				break;
			case $button == 'underline':
				$function = 'iUnderline';
				$label = 'U';
				break;
			case $button == 'code':
				$function = 'iCode';
				$label = '<>';
				break;
			case $button == 'media':
				$function = 'iMedia';
				$label = 'Media';
				$href = $config['media_url'];
				$return = '<input type="button" value="' . $label . '" class="wysiwyg-button wysiwyg-toolbar-button" data-function="' . $function . '" data-arguments="' . $href . '">';
				break;
			case $button == 'link':
				$value = __('Add');
				$return = <<<EOT
<input type="button" value="Link" class="drop-menu-button">
<div class="drop-menu-content is-hidden">
	<input type="text" name="wysiwyg_link_label" id="wysiwyg_link_label" class="wysiwyg-selected-label">
	<input type="url" name="wysiwyg_link" id="wysiwyg_link">
	<input type="button" value="$value" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iLink">
</div>
EOT;
				break;
			case $button == 'list':
				$return = <<<EOT
<input type="button" value="List" class="drop-menu-button">
<div class="drop-menu-content is-hidden">
	<input type="button" value="Ordered" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iList" data-arguments="insertOrderedList">
	<input type="button" value="Unordered" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iList" data-arguments="insertUnorderedList">
</div>
EOT;
				break;
			case $button == 'header':
				$return = <<<EOT
<input type="button" value="H" class="drop-menu-button">
<div class="drop-menu-content is-hidden">
	<input type="button" value="H1" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iHeader" data-arguments="h1">
	<input type="button" value="H2" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iHeader" data-arguments="h2">
	<input type="button" value="H3" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iHeader" data-arguments="h3">
	<input type="button" value="H4" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iHeader" data-arguments="h4">
	<input type="button" value="H5" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iHeader" data-arguments="h5">
	<input type="button" value="H6" class="wysiwyg-button wysiwyg-toolbar-button" data-function="iHeader" data-arguments="h6">
</div>
EOT;
				break;
		}

		if (!isset($return)) {
			if (isset($function) && isset($label)) {
				$return = '<input type="button" value="' . $label . '" class="wysiwyg-button wysiwyg-toolbar-button" data-function="' . $function . '">';
			} else {
				return false;
			}
		}

		return $return;
	}
}
