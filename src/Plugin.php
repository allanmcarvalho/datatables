<?php
/**
 * Copyright (c) Allan Carvalho 2019.
 * Under Mit License
 * php version 7.2
 *
 * @category CakePHP
 * @package  DataRenderer\Core
 * @author   Allan Carvalho <allan.m.carvalho@outlook.com>
 * @license  MIT License https://github.com/allanmcarvalho/cakephp-data-renderer/blob/master/LICENSE
 * @link     https://github.com/allanmcarvalho/cakephp-data-renderer
 */
declare(strict_types = 1);

namespace DataTables;

require __DIR__ . DS . '..' . DS . 'config' . DS . 'paths.php';

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Error\FatalErrorException;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;

/**
 * Plugin for DataTables
 */
class Plugin extends BasePlugin {

	/**
	 * Load all the plugin configuration and bootstrap logic.
	 *
	 * The host application is provided as an argument. This allows you to load
	 * additional plugin dependencies, or attach events.
	 *
	 * @param \Cake\Core\PluginApplicationInterface $app The host application
	 * @return void
	 */
	public function bootstrap(PluginApplicationInterface $app): void {
		$applicationDataTablesConfigs = Configure::read('DataTables', []);
		if (!is_array($applicationDataTablesConfigs)) {
			throw new FatalErrorException('DataTables config key must contain an array');
		}
		Configure::load('DataTables.app', 'default', true);
		foreach ($applicationDataTablesConfigs as $config => $value) {
			$this->mergeConfiguration('DataTables', (string)$config, $value);
		}

	}

	/**
	 * Merge item by item between plugin and application configuration
	 *
	 * @param string $currentPath Current Path to save in configuration
	 * @param string $config Configuration key
	 * @param mixed $value Configuration value
	 * @return void
	 */
	private function mergeConfiguration(string $currentPath, string $config, $value) {
		if (is_array($value)) {
			foreach ($value as $childConfig => $childValue) {
				$this->mergeConfiguration("$currentPath.$config", (string)$childConfig, $childValue);
			}
		} elseif (!empty($value)) {
			Configure::write("$currentPath.$config", $value);
		}
	}

	/**
	 * Add routes for the plugin.
	 *
	 * If your plugin has many routes and you would like to isolate them into a separate file,
	 * you can create `$plugin/config/routes.php` and delete this method.
	 *
	 * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
	 * @return void
	 */
	public function routes(RouteBuilder $routes): void {
		$routes->plugin(
			'DataTables',
			['path' => '/data-tables'],
			function (RouteBuilder $builder) {
				// Add custom routes here

				$builder->fallbacks();
			}
		);
		parent::routes($routes);
	}

	/**
	 * Add middleware for the plugin.
	 *
	 * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to update.
	 * @return \Cake\Http\MiddlewareQueue
	 */
	public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue {
		// Add your middlewares here

		return $middlewareQueue;
	}

}
