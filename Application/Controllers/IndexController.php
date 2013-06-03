<?php

namespace Application\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

class IndexController extends Controller
{

	public function index ()
	{
		/*$this->container['localization']->load('index');
		$this->container['localization']->load('index', 'application', 'en');

		$data = array
		(
			'title'       => 'Page title',
			'description' => 'Page description',
			'keywords'    => 'page, keywords',
			'headline'    => 'Page headline',
			'paragraph1'  => $this->container['localization']->get('paragraph'),
			'paragraph2'  => $this->container['localization']->get('paragraph', array(), 'en'),
		);
		return $this->render('index', $data);*/

		$styles = array
		(
			'/files/bootstrap/less/accordion.less',
			'/files/bootstrap/less/alerts.less',
			'/files/bootstrap/less/bootstrap.less',
			'/files/bootstrap/less/breadcrumbs.less',
			'/files/bootstrap/less/button-groups.less',
			'/files/bootstrap/less/buttons.less',
			'/files/bootstrap/less/carousel.less',
			'/files/bootstrap/less/close.less',
			'/files/bootstrap/less/code.less',
			'/files/bootstrap/less/component-animations.less',
			'/files/bootstrap/less/dropdowns.less',
			'/files/bootstrap/less/forms.less',
			'/files/bootstrap/less/grid.less',
			'/files/bootstrap/less/hero-unit.less',
			'/files/bootstrap/less/labels-badges.less',
			'/files/bootstrap/less/layouts.less',
			'/files/bootstrap/less/media.less',
			'/files/bootstrap/less/mixins.less',
			'/files/bootstrap/less/modals.less',
			'/files/bootstrap/less/navbar.less',
			'/files/bootstrap/less/navs.less',
			'/files/bootstrap/less/pager.less',
			'/files/bootstrap/less/pagination.less',
			'/files/bootstrap/less/popovers.less',
			'/files/bootstrap/less/progress-bars.less',
			'/files/bootstrap/less/reset.less',
			'/files/bootstrap/less/responsive.less',
			'/files/bootstrap/less/responsive-767px-max.less',
			'/files/bootstrap/less/responsive-768px-979px.less',
			'/files/bootstrap/less/responsive-1200px-min.less',
			'/files/bootstrap/less/responsive-navbar.less',
			'/files/bootstrap/less/responsive-utilities.less',
			'/files/bootstrap/less/scaffolding.less',
			'/files/bootstrap/less/sprites.less',
			'/files/bootstrap/less/tables.less',
			'/files/bootstrap/less/thumbnails.less',
			'/files/bootstrap/less/tooltip.less',
			'/files/bootstrap/less/type.less',
			'/files/bootstrap/less/utilities.less',
			'/files/bootstrap/less/variables.less',
			'/files/bootstrap/less/wells.less',
		);

		$scripts = array
		(
			'/files/bootstrap/assets/jquery.js',
			'/files/bootstrap/js/bootstrap-affix.js',
			'/files/bootstrap/js/bootstrap-alert.js',
			'/files/bootstrap/js/bootstrap-button.js',
			'/files/bootstrap/js/bootstrap-carousel.js',
			'/files/bootstrap/js/bootstrap-collarse.js',
			'/files/bootstrap/js/bootstrap-dropdown.js',
			'/files/bootstrap/js/bootstrap-modal.js',
			'/files/bootstrap/js/bootstrap-popover.js',
			'/files/bootstrap/js/bootstrap-scrollspy.js',
			'/files/bootstrap/js/bootstrap-tab.js',
			'/files/bootstrap/js/bootstrap-tooltip.js',
			'/files/bootstrap/js/bootstrap-transition.js',
			'/files/bootstrap/js/bootstrap-typeahead.js',
		);

		$data = array
		(
			'styles'       => $this->container['assets']['dispatcher']->getAssets('frontend.min', $styles, 'less'),
			'scripts'      => $this->container['assets']['dispatcher']->getAssets('frontend.min', $scripts, 'js'),
		);
		return $this->render('index', $data);
	}
}