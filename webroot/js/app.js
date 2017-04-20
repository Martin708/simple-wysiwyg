
/**
 * Created by Martin on 2016-08-17.
 */

var wysiwygEditor;
var $editor = document.getElementById('wysiwygEditor')
wysiwygEditor = $editor.contentWindow.document;

var addButton = document.querySelector('.wysiwyg-add-button')
var toolbarButton = document.querySelectorAll('.wysiwyg-toolbar-button')

function iFrameOn()
{
	wysiwygEditor.designMode = 'On'
}

function getSelectionStart() {
	var node = wysiwygEditor.getSelection().anchorNode;
	return (node.nodeType == 3 ? node.parentNode : node)
}

wysiwygEditor.onkeydown = function(e) {
	var selection = getSelectionStart()
	var nodeName = selection.nodeName
	var text = selection.innerText
	console.log(text.length + ',', 'node: ' + nodeName)

	if (e.keyCode === 13 && nodeName != 'LI' && nodeName != 'OL') {
		wysiwygEditor.execCommand('formatBlock', false, '<P>')
		console.log('first')
	} else {
		if (nodeName == 'BODY' || nodeName == 'DIV') {
			wysiwygEditor.execCommand('formatBlock', false, '<P>')
			console.log('second')
		} else {
			console.log('third')
		}
	}
}

window.addEventListener('click', (e) => {
	if (e.target && e.target.classList.contains('wysiwyg-button')) {
		var target = e.target

		if (target.dataset.function) {
			var func = target.dataset.function
			var args = target.dataset.arguments

			window[func](args)
		}
	}
})

/*for(var i=0; i < toolbarButton.length; i++) {
	var button = toolbarButton[i]
	button.addEventListener('click', (e) => {
		var func = e.target.dataset.function
		var args = e.target.dataset.arguments
		console.log(e.target.dataset)
		window[func](args)
	})
}

addButton.addEventListener('click', (e) => {
	e.preventDefault()
	var func
	var input = e.target.dataset.id

	switch (input)
	{
		case 'wysiwyg_link':
			func = 'iLink'
			break

		default:
			alert('Error')
			break
	}

	window[func](e)
})*/

wysiwygEditor.addEventListener('paste', (e) => {
	e.preventDefault()
	var pastData = e.clipboardData.getData('text/plain')


	pastData = pastData.replace(/\n|<br \/>/ig, '')

	// insert text manually
	wysiwygEditor.execCommand("insertHTML", false, pastData)
})

function sendToEditor(html)
{
	$editor.focus()
	wysiwygEditor.execCommand("insertHTML", false, html)
}

function iLink()
{
	// TODO test if value is a valid link
	// TODO add a _BLANK option
	var $inputHref = document.getElementById('wysiwyg_link')
	var $inputLabel = document.getElementById('wysiwyg_link_label')

	var inputHref = $inputHref.value
	var inputLabel = $inputLabel.value
	console.log(inputHref)

	if (isURL(inputHref)) {
		document.getElementById('wysiwygEditor').focus()
		if (inputLabel == '')
			inputLabel = inputHref

		var html = '<a href="' + inputHref +'">' + inputLabel + '</a>'
		wysiwygEditor.execCommand("insertHTML", false, html)
	} else {
		alert('This URL is not valid.')
	}
}

function iList(type)
{
	//console.log(type)
	//if (type == 'insertUnorderedList' || type == 'insertOrderedList') {
		$editor.focus()
		wysiwygEditor.execCommand(type, false, null)
	//}

	/*console.log('Type must be insertOrderedList or insertUnorderedList')
	return false*/
}

function iMedia(href)
{
	$('.wysiwyg-popup').load(href, function(responseTxt, statusTxt, xhr){
		if(statusTxt == "error")
			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	})
}

function iBold()
{
	$editor.focus()
	wysiwygEditor.execCommand('bold', false, null)
}

function iItalic()
{
	wysiwygEditor.execCommand('italic', false, null)
	$editor.focus()
}

function iUnderline()
{
	wysiwygEditor.execCommand('underline', false, null)
	$editor.focus()
}

function iHeader(h)
{
	wysiwygEditor.execCommand('formatBlock', false, h)
	$editor.focus()
}

function iCode()
{
	var $iCodeEditor = document.getElementById('wysiwygEditor')
	var $wysiwygTextarea = document.getElementById('wysiwygTextarea')

	if ($iCodeEditor.style.display === 'none') {
		var textareaValue = $wysiwygTextarea.value
		textareaValue = textareaValue.replace(/\n/ig, '')

		$('#wysiwygEditor').contents().find('body').html(textareaValue)
	} else {
		var iFrameValue = $('#wysiwygEditor').contents().find('body').html()
		iFrameValue = iFrameValue.replace(/\n/ig, '')
		iFrameValue = iFrameValue.replace(/(<\/(p|h[1-9]|div|ul|li|ol|dl)>)/ig, '$1\n')

		$wysiwygTextarea.value = iFrameValue
	}

	$iCodeEditor.style.display = $iCodeEditor.style.display === 'none' ? '' : 'none'
	$wysiwygTextarea.style.display = $wysiwygTextarea.style.display === 'none' ? '' : 'none'
	//getCarretPosition(document.getElementById('wysiwygEditor'))
}

function getCarretPosition(wysiwygEditor)
{
	var ifr = wysiwygEditor
	var idoc = ifr.contentDocument
	var ibody = ifr.contentDocument.body // content: "teststring|"

	var caret = 2

	var sel = ifr.contentDocument.getSelection()
	var range = sel.getRangeAt(0)

	console.log(sel)
	console.log(range.startOffset)
	console.log(range.endOffset)
	if (range.startOffset === range.endOffset) {
		el = ibody
		range.setStart(el, range.startOffset)
		range.setEnd(el, range.endOffset)

		sel.removeAllRanges()
		sel.addRange(range)
	}


	/*var el = ibody
	range.setStart(el, caret)
	range.setEnd(el, caret)

	sel.removeAllRanges()
	sel.addRange(range)*/
}

function getSelectionText() {
	var selection = ""

	var iframe = document.getElementById('wysiwygEditor')
	var idoc = iframe.contentDocument || iframe.contentWindow.document

	selection = idoc.getSelection().toString()

	return selection
}

function isURL(str) {
	var a  = document.createElement('a');
	a.href = str;
	return (a.host && a.host != window.location.host);
}

$(function() {
	$('body').on('click', '.drop-menu-button', function() {
		var selection = getSelectionText()

		var $dropMenuContent = $(this).next('.drop-menu-content')
		var $dropMenuContentLabel = $dropMenuContent.children('.wysiwyg-selected-label')

		$('.drop-menu-content').not($dropMenuContent).addClass('is-hidden')

		$dropMenuContent.toggleClass('is-hidden')
		$dropMenuContentLabel.focus()
		$dropMenuContentLabel.val(selection)
	})
})