<?php

class Zend_View_Helper_MenuTop extends Zend_View_Helper_Navigation_Menu
{

    public function menuTop(Zend_Navigation_Container $container = null)
    {
        parent::menu($container);
        return $this;
    }

    public function htmlify(Zend_Navigation_Page $page)
    {
        if ($this->accept($page))
        {
            // get label and title for translating
            $label = $page->getLabel();
            $title = $page->getTitle();
            

            // translate label and title?
            if ($this->getUseTranslator() && $t = $this->getTranslator())
            {
                if (!empty($label) && is_string($label))
                {
                    $label = $t->translate($label);
                }
                if (!empty($title) && is_string($title))
                {
                    $title = $t->translate($title);
                }
                if (!empty($page->description) && is_string($page->description))
                {
                    $page->description = $t->translate($page->description);
                }
            }

            // get attribs for element
            $attribs = array(
                'id'    => $page->getId(),
                'title' => $title,
                'class' => $page->getClass()
            );

            // does page have a href?
            if ($href = $page->getHref())
            {
                $element           = 'a';
                $attribs['href']   = $href;
                $attribs['target'] = $page->getTarget();
            }
            else
            {
                $element = 'span';
            }

            $label = '<strong>'.$this->view->escape($label).'</strong>';
            if (!empty($page->description))
            {
                $label .= '<span>'.$this->view->escape($page->description).'</span>';
            }

            return '<' . $element . $this->_htmlAttribs($attribs) . '>' . $label . '</' . $element . '>';
        }
    }

}