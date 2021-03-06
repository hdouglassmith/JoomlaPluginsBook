<?php
/**
 * Content Plugin for Joomla! - Chapter 04 / Test 02
 *
 * @author     Jisse Reitsma <jisse@yireo.com>
 * @copyright  Copyright 2014 Jisse Reitsma
 * @license    GNU Public License version 3 or later
 * @link       http://www.yireo.com/books/
 */

defined('JPATH_BASE') or die();

jimport('joomla.form.formfield');

/**
 * Class JFormFieldTestselect2
 *
 * @since  September 2014
 */
class JFormFieldTestselect2 extends JFormField
{
	public $type = 'Test select 2';

	/**
	 * Method to get the input field for this class.
	 *
	 * @return	string
	 */
	protected function getInput()
	{
		$db = JFactory::getDbo();
		$version = new JVersion;

		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('url', 'title')));
		$query->from($db->quoteName('#__weblinks'));

		if (floatval($version->RELEASE) <= '2.5')
		{
			$query->where($db->quoteName('approved') . ' = 1');
		}
		else
		{
			$query->where($db->quoteName('state') . ' = 1');
		}

		$query->order($db->quoteName('title'));
		$db->setQuery($query);

		$options = array();
		$rows = $db->loadObjectList();

		if (!empty($rows))
		{
			foreach ($rows as $row)
			{
				$options[] = JHTML::_('select.option', $row->url, $row->title, 'value', 'text');
			}
		}

		$multiple = 0;
		$size = 3;
		$attribs = 'class="inputbox"';

		if (isset($this->element['multiple']))
		{
            $multiple= filter_var($this->element['multiple'], FILTER_VALIDATE_BOOLEAN); 
		}

		if (isset($this->element['size']))
		{
			$size = (int) $this->element['size'];
		}

		if ($size < 3)
		{
			$size = 3;
		}

		if ($multiple == true)
		{
			$attribs .= ' multiple="multiple"';
		}

		if ($size > 0)
		{
			$attribs .= ' size="' . $size . '"';
		}

		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'value', 'text', $this->value, $this->name);
	}
}
