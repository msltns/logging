<?php

namespace msltns\logging;

use msltns\logging\Grafana_Loki_Handler;
use msltns\logging\Logstream_Handler;

/**
 * Class Logstream provides a way to log debug messages to an API.
 * 
 * @category 	Class
 * @package  	Utilities
 * @author 		msltns <info@msltns.com>
 * @version  	0.0.1
 * @since   	0.0.1
 * @license 	GPL 3
 *          	This program is free software; you can redistribute it and/or modify
 *          	it under the terms of the GNU General Public License, version 3, as
 *          	published by the Free Software Foundation.
 *          	This program is distributed in the hope that it will be useful,
 *          	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *          	GNU General Public License for more details.
 *          	You should have received a copy of the GNU General Public License
 *          	along with this program; if not, write to the Free Software
 *          	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! class_exists( '\msltns\logging\Logstream' ) ) {
	
	class Logstream {
		
		/**
		 * @var \Logstream
		 */
		private static $instance;
		
        /**
         * @var string platform
         */
		protected $platform = '';
        
        /**
         * @var string environment
         */
		protected $environment = '';
        
        /**
         * @var string service
         */
		protected $service = '';
		
		/**
		 * Main constructor.
		 *
		 * @return void
		 */
		protected function __construct() {
		}
		
		/**
		 * Singleton instance.
		 * 
         * @param   string  $plattform      The platform.
         * @param   string  $environment    The environment.
         * @param   string  $service        The service.
		 * @return \Logstream
		 */
		public static function instance( string $platform = '', string $environment = '', string $service = '' ) {
			return self::getInstance( $platform, $environment, $service );
		}
        
		/**
		 * Singleton instance.
		 * 
         * @param   string  $plattform      The platform.
         * @param   string  $environment    The environment.
         * @param   string  $service        The service.
		 * @return \Logstream
		 */
		public static function get_instance( string $platform = '', string $environment = '', string $service = '' ) {
			return self::getInstance( $platform, $environment, $service );
		}
		
		/**
		 * Singleton instance.
		 * 
         * @param   string  $plattform      The platform.
         * @param   string  $environment    The environment.
         * @param   string  $service        The service.
		 * @return \Logstream
		 */
		public static function getInstance( string $platform = '', string $environment = '', string $service = '' ) {
			
			if ( !isset( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->init( $platform, $environment, $service );
			}
            
			return self::$instance;
		}
		
		/**
		 * Initialization method.
		 * 
         * @param   string  $plattform      The platform.
         * @param   string  $environment    The environment.
         * @param   string  $service        The service.
		 * @return void
		 */
		private function init( string $platform = '', string $environment = '', string $service = '' ): void {
            
            if ( empty( $platform ) ) {
                if ( defined( 'LOGSTREAM_PLATFORM' ) ) {
                    $platform = LOGSTREAM_PLATFORM;
                } else {
                    $platform = parse_url( $_SERVER['HTTP_HOST'], PHP_URL_HOST );
                }				
			}
            
            if ( empty( $environment ) && defined( 'LOGSTREAM_ENVIRONMENT' ) ) {
                $environment = LOGSTREAM_ENVIRONMENT;
            }
            
            if ( empty( $service ) && defined( 'LOGSTREAM_SERVICE' ) ) {
                $service = LOGSTREAM_SERVICE;
            }
			
            $this->platform    = $platform;
            $this->environment = $environment;
            $this->service     = $service;
		}
		
		/**
		 * Writes a debug message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function debug( string $message, array $context = [] ) {
			$this->log( $message, 'debug', $context );
		}
		
		/**
		 * Writes an info message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function info( string $message, array $context = [] ) {
			$this->log( $message, 'info', $context );
		}
		
		/**
		 * Writes a notice message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function notice( string $message, array $context = [] ) {
			$this->log( $message, 'notice', $context );
		}
        
		/**
		 * Writes a warning message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function warn( string $message, array $context = [] ) {
			$this->log( $message, 'warning', $context );
		}
		
		/**
		 * Writes a warning message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function warning( string $message, array $context = [] ) {
			$this->log( $message, 'warning', $context );
		}
        
		/**
		 * Writes an error message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function error( string $message, array $context = [] ) {
			$this->log( $message, 'error', $context );
		}
        
		/**
		 * Writes a critical message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function critical( string $message, array $context = [] ) {
			$this->log( $message, 'critical', $context );
		}
        
		/**
		 * Writes an alert message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function alert( string $message, array $context = [] ) {
			$this->log( $message, 'alert', $context );
		}
        
		/**
		 * Writes an emergency message to a logfile.
		 *
		 * @param   string 	$message
		 * @param	array 	$context
		 * @return  void
		 */
		public function emergency( string $message, array $context = [] ) {
			$this->log( $message, 'emergency', $context );
		}
		
		/**
		 * Output a debug message.
		 *
		 * @param string 	$message 	Debug message.
		 * @param string 	$level   	Debug level.
		 * @param array     $context   	Debug context parameters.
		 * @return void
		 */
		public function log( string $message, string $level = 'info', array $context = [] ): void {
            
            $level = strtolower( $level );
            
			if ( !in_array( $level, [ 'debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency' ] ) ) {
				$level = 'info';
			}
            
            $message = trim( $message );
            
            $handler = '\msltns\logging\Logstream_Handler';
            if ( defined( 'LOGSTREAM_HANDLER' ) && !empty( LOGSTREAM_HANDLER ) ) {
                $handler = LOGSTREAM_HANDLER;
            }
            
            if ( class_exists( $handler ) ) {
                
                try {
    				$handler::getInstance( $this->platform, $this->environment, $this->service )->log( $message, $level, $context );
    			} catch ( \Exception $e ) {
    				error_log( '[ERROR] ' . $e->getMessage() );
    				error_log( '[' . strtoupper( $level ) . '] ' . $message );
    			}
                
            } else {
                error_log( "class {$handler} does not exist" );
            }
            
		}
		
	}
	
}
