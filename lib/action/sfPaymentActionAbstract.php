<?php

  /**
   * sfPaymentActionAbstract
   * 
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  abstract class sfPaymentActionAbstract extends sfAction
  {

    /**
     * Process a form object.
     *
     * @param   sfPaymentFormAbstract $arg_form     The form to process
     * @param   sfWebRequest          $arg_request  The request object
     * @param   mixed                 $arg_route    The route to which the form
     *                                              data should be submitted
     * @param   string                $arg_method   The form submit method
     *                                
     * @return  string                              The view type to render
     */
    protected function _processForm (sfPaymentFormAbstract $arg_form, sfWebRequest $arg_request, $arg_route, $arg_method = 'post')
    {
      $name = $arg_form->getName();

      if ($arg_request->isMethod($arg_method) && $arg_request->hasParameter($name))
      {
        $files = $arg_form->isMultipart() ? $arg_request->getFiles($name) : NULL;

        $arg_form->bind($arg_request->getParameter($name), $files);

        if ($arg_form->isValid())
        {
          $route = $this->_doProcessForm($arg_form, $arg_request, $arg_route);

          if (NULL !== $route)
          {
            $this->redirect($route);
          }
        }
      }

      return sfView::INPUT;
    }

    /**
     * Process a form object when it is valid.
     *
     * @param   sfForm        $arg_form     The form to process
     * @param   sfWebRequest  $arg_request  The request object
     * @param   String        $arg_route    The route to which the form data
     *                                      should be submitted
     *
     * @return  array                       Representing the routing data
     */
    protected function _doProcessForm (sfForm $arg_form, sfWebRequest $arg_request, $arg_route)
    {
      return array('sf_route'   => $arg_route
                  ,'sf_subject' => $arg_form->save()
                  );
    }

  }