<?php

  /**
   * sfPaymentActionAbstract.
   * 
   * @author    Marijn Huizendveld <marijn@round84.com>
   * @version   $Revision: 209 $ changed by $Author: marijn $
   *
   * @copyright Round 84 (2009)
   */
  abstract class sfPaymentActionAbstract extends sfAction
  {

    /**
     * Process a form object.
     *
     * @param   sfPaymentFormAbstract $arg_form     The form to process.
     * @param   sfWebRequest          $arg_request  The request object.
     * @param   String                $arg_route    The route to which the form data should be submitted.
     * @param   String                $arg_method   The form submit method.
     *                                
     * @return  String                              The view type to render.
     */
    protected function _processForm (sfPaymentFormAbstract $arg_form, sfWebRequest $arg_request, $arg_route, $arg_method = 'post')
    {
      $name = $arg_form->getName();

      if ($arg_request->isMethod($arg_method) && $arg_request->hasParameter($name))
      {
        $arg_form->bind($arg_request->getParameter($name), $arg_form->isMultipart() ? $arg_request->getFiles($name) : NULL);

        if ($arg_form->isValid())
        {
          $this->redirect(array('sf_route'   => $arg_route
                               ,'sf_subject' => $this->_doProcessForm($arg_form)
                               ));
        }
      }

      return sfView::INPUT;
    }

    /**
     * Process a form object when it is valid.
     *
     * @param   sfPaymentFormAbstract $arg_form     The form to process.
     *
     * @return  mixed
     */
    protected function _doProcessForm (sfPaymentFormAbstract $arg_form)
    {
      return $arg_form->process();
    }

  }