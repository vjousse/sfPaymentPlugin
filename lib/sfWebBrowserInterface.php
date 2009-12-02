<?php

  /**
   * sfWebBrowserInterface is used to define the interface for make requests to
   * the gateway implementations.
   *
   * @package   sfPaymentPlugin
   * @author    Marijn Huizendveld <marijn@round84.com>
   *
   * @version   $Revision$ changed by $Author$
   */
  interface sfWebBrowserInterface
  {

    /**
     * Perform a HTTP GET request.
     *
     * @param   string                $uri        The URI to send the request to.
     * @param   array                 $parameters The request parameters.
     * @param   array                 $headers    The headers to send with the request.
     *
     * @return  sfWebBrowserInterface
     */
    function get ($uri, $parameters = array(), $headers = array());

    /**
     * Perform a HTTP HEAD request.
     *
     * @param   string                $uri        The URI to send the request to.
     * @param   array                 $parameters The request parameters.
     * @param   array                 $headers    The headers to send with the request.
     *
     * @return  sfWebBrowserInterface
     */
    function head ($uri, $parameters = array(), $headers = array());

    /**
     * Perform a HTTP POST request.
     *
     * @param   string                $uri        The URI to send the request to.
     * @param   array                 $parameters The request parameters.
     * @param   array                 $headers    The headers to send with the request.
     *
     * @return  sfWebBrowserInterface
     */
    function post ($uri, $parameters = array(), $headers = array());

    /**
     * Perform a HTTP PUT request.
     *
     * @param   string                $uri        The URI to send the request to.
     * @param   array                 $parameters The request parameters.
     * @param   array                 $headers    The headers to send with the request.
     *
     * @return  sfWebBrowserInterface
     */
    function put ($uri, $parameters = array(), $headers = array());

    /**
     * Perform a HTTP DELETE request.
     *
     * @param   string                $uri        The URI to send the request to.
     * @param   array                 $parameters The request parameters.
     * @param   array                 $headers    The headers to send with the request.
     *
     * @return  sfWebBrowserInterface
     */
    function delete ($uri, $parameters = array(), $headers = array());

    /**
     * Get a DOMDocument version of the response.
     *
     * @return DOMDocument The reponse contents.
     */
    function getResponseDom ();

  }