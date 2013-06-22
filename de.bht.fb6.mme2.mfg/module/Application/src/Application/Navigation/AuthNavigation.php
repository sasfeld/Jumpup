<?php
namespace Application\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Navigation\Page\AbstractPage;

use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;



use JumpUpDriver\Util\Routes\IRouteStore;
use JumpUpDriver\Util\Messages\ILabels;
use JumpUpUser\Util\Auth\CheckAuthentication;
use Zend\Authentication\AuthenticationService;
use Zend\Navigation\Navigation;

/**
 *
 * This special navigation realizes a navigation which depends on the authentication state (is the user authenticated or not).
 *
 * Example:
 * - user isn't authenticated: show login and registrate links/pages
 * - user is authenticated: show addtrip link/page
 *
 *
 * @package    Application\Navigation
 * @subpackage
 * @copyright  Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license    GNU license
 * @version    1.0
 * @since      30.04.2013
 */
class AuthNavigation extends Navigation {
    private $_authService;
    private $_serviceLocator;

    /**
     * Construct and init a new AuthNavigation
     * @param array of AbstractPage(s) $pages
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, $pages) {
        parent::__construct($pages);
        $this->_serviceLocator = $serviceLocator;
        $this->_authService = $serviceLocator->get('AuthService');
        $this->_initialize();
    }

    /**
     * Manually inject the pages.
     * @param AbstractPage $page
     * @param RouteMatch $routeMatch
     * @param Router $router
     */
    private function _injectPage(AbstractPage $page, RouteMatch $routeMatch, Router $router) {
         
        $page->routeMatch = $routeMatch;    // which route matches to this page?
        $page->router = $router;
    }
    /**
     * Add the pages depending on the authentication state.
     */
    private function _initialize() {
        $application = $this->_serviceLocator->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();
        $translator  = $this->_serviceLocator->get('Translator');
         
        if(null !== $routeMatch) {
            if(CheckAuthentication::isAuthorized($this->_authService, "")) {
                // show pages within the authentication area
                /*
                 * ..:: Module JumpUpDriver -> list vehicles page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' =>  $translator->translate(\JumpUpDriver\Util\Messages\ILabels::MAINNAV_MANAGEPROFILE),
                'route' => \JumpUpUser\Util\Routes\IRouteStore::SHOW_PROFILE,
                'class' => 'fNiv',
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..::::::::::::::::::::::::::::::::::::::::::::::::..
                 */
                /*
                 * ..:: Module JumpUpDriver -> addtrip page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' =>  $translator->translate(\JumpUpDriver\Util\Messages\ILabels::MAINNAV_ADDTRIP),
                'route' => \JumpUpDriver\Util\Routes\IRouteStore::ADD_TRIP,
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..:::::::::::::::::::::::::::::::::::::::::..
                 */
                /*
                 * ..:: Module JumpUpPassenger -> LookUpTrips page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' =>  $translator->translate(\JumpUpPassenger\Util\Messages\ILabels::MAINNAV_LOOKUP_TRIPS),
                'route' => \JumpUpPassenger\Util\Routes\IRouteStore::LOOKUP_TRIPS,
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..:::::::::::::::::::::::::::::::::::::::::..
                 */
                /*
                 * ..:: Module JumpUpPassenger -> Bookings page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' =>  $translator->translate(\JumpUpPassenger\Util\Messages\ILabels::MAINNAV_PASSENGER_BOOKINGS),
                'route' => \JumpUpPassenger\Util\Routes\IRouteStore::BOOK_PASS_OVERVIEW,
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..:::::::::::::::::::::::::::::::::::::::::..
                 */
                /*
                 * ..:: Module JumpUpDriver -> Bookings page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' =>  $translator->translate(\JumpUpDriver\Util\Messages\ILabels::MAINNAV_DRIVER_BOOKINGS),
                'route' => \JumpUpDriver\Util\Routes\IRouteStore::BOOK_DRIVER_OVERVIEW,
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..:::::::::::::::::::::::::::::::::::::::::..
                 */
                /*
                 * ..:: Module JumpUpUser -> logout page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' =>  $translator->translate(\JumpUpUser\Util\Messages\ILabels::MAINNAV_LOGOUT),
                'route' => \JumpUpUser\Util\Routes\IRouteStore::LOGOUT,
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..:::::::::::::::::::::::::::::::::::::::::..
                 */
            }
            else { // user is not authenticated
                /*
                 * ..:: Module JumpUpUser -> registration page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' => $translator->translate(\JumpUpUser\Util\Messages\ILabels::MAINNAV_REGISTER),
                'route' => \JumpUpUser\Util\Routes\IRouteStore::REGISTER,
                'class' => 'fNiv',
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..::::::::::::::::::::::::::::::::::::::::::..
                 */
                /*
                 * ..:: Module JumpUpUser -> login page ::..
                 */
                $page = \Zend\Navigation\Page\AbstractPage::factory(array(
                'label' => $translator->translate(\JumpUpUser\Util\Messages\ILabels::MAINNAV_LOGIN),
                'route' => \JumpUpUser\Util\Routes\IRouteStore::LOGIN,
                ));
                $this->_injectPage($page, $routeMatch, $router);
                $this->addPage($page);
                /*
                 * ..:::::::::::::::::::::::::::::::::::::..
                 */
            }
        }
        // else: 404 just don't do anything 


         

    }
}