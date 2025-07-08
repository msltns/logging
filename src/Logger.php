<?php

namespace msltns\logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

use msltns\logging\Formatter;

/**
 * Class Logger provides several useful helper methods.
 *
 * @category 	Class
 * @package  	Utilities
 * @author 		Daniel Muenter <info@msltns.com>
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
if ( ! class_exists( '\msltns\logging\Logger' ) ) {
	
	// use msltns\logging\Logger;
	// Logger::getInstance()->log( 'msg', 'info' );
	class Logger {
	
		private static $instance;
		private $logger;
		
		/**
		 * Main constructor.
		 *
		 * @return void
		 */
		private function __construct() {
		
		}
        
		/**
		 * Singleton instance.
		 * 
         * @param   string  $name   The logger name.
         * @param   string  $path   The logfile path.
		 * @return \Logger
		 */
		public static function instance( string $name = '', string $path = '' ) {
			return self::getInstance( $name, $path );
		}
        
		/**
		 * Singleton instance.
		 * 
         * @param   string  $name   The logger name.
         * @param   string  $path   The logfile path.
		 * @return \Logger
		 */
		public static function get_instance( string $name = '', string $path = '' ) {
			return self::getInstance( $name, $path );
		}
		
		/**
		 * Singleton instance.
		 * 
         * @param   string  $name   The logger name.
         * @param   string  $path   The logfile path.
		 * @return \Logger
		 */
		public static function getInstance( string $name = '', string $path = '' ) {
			if ( !isset( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->init( $name, $path );
			}
            
			return self::$instance;
		}
        
		/**
		 * Initialization method.
		 * 
		 * @param 	string	$name
		 * @param	string	$path
		 * @return 	void
		 */
		private function init( $name = '', $path = '' ) {
			if ( empty( $name ) ) {
				$name = 'wpwoodo';
			}
			if ( empty( $path ) ) {
				$path = __DIR__ . '/../../../../debug.log';
			}
            
            // Avoid errors due to not writable log file
            if ( is_writable( $path ) ) {
    			$streamHandler = new StreamHandler( $path, MonologLogger::DEBUG );
    			$streamHandler->setFormatter( new Formatter( $name ) );
		
    			$this->logger = new MonologLogger( $name );
    			$this->logger->pushHandler( $streamHandler );
            }
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
		 * Writes a debug message to a logfile.
		 *
		 * @param   mixed 	$message
		 * @param   string	$level
		 * @param	array 	$context
		 * @return  void
		 */
		public function log( mixed $message, string $level = 'info', array $context = [] ) {
		
			if ( !isset( $this->logger ) ) {
				self::init();
			}
            
            if ( is_null( $this->logger ) ) {
                // logger couldn't be initialized
                return;
            }
		
			if ( !in_array( $level, [ 'debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency' ] ) ) {
				$level = 'info';
			}
		
			if ( is_array( $message ) || is_object( $message ) ) {
	            $message = print_r( $message, true );
	        }
            
            $this->logger->{$level}( $message, $context );
		}
	}
}
