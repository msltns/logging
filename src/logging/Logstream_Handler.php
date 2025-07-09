<?php

namespace msltns\logging;

/**
 * Class Logstream_Handler provides several useful logging methods.
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

if ( ! class_exists( '\msltns\logging\Logstream_Handler' ) ) {
	
	class Logstream_Handler {
		
		/**
		 * @var \Logstream_Handler
		 */
		protected static $instance;
		
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
         */
		private function __construct() {
		}
		
		/**
		 * Singleton instance.
		 * 
         * @param   string  $plattform      The platform.
         * @param   string  $environment    The environment.
         * @param   string  $service        The service.
		 * @return \Logstream_Handler
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
		 * @return \Logstream_Handler
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
		 * @return \Logstream_Handler
		 */
		public static function getInstance( string $platform = '', string $environment = '', string $service = '' ) {
            
			if ( ! defined( 'LOGSTREAM_API_URL' ) || empty( LOGSTREAM_API_URL ) ) {
				throw new \Exception( 'Constant LOGSTREAM_API_URL is not defined' );
			}
            
			if ( ! defined( 'LOGSTREAM_API_TOKEN' ) || empty( LOGSTREAM_API_TOKEN ) ) {
				throw new \Exception( 'Constant LOGSTREAM_API_TOKEN is not defined' );
			}
            
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
		protected function init( string $platform = '', string $environment = '', string $service = '' ): void {
            $this->platform    = $platform;
            $this->environment = $environment;
            $this->service     = $service;
		}
		
		/**
		 * Sends debug messages to a logstream API.
		 * 
		 * @param   string 	$message
		 * @param   string	$level
		 * @param   array	$context
		 * @return  void
		 */
		public function log( string $message, string $level, array $context = [] ): void {
			
            $params = [
				'sender' 	    => $this->platform,
                'environment'   => $this->environment,
				'message' 	    => $message,
				'level' 	    => $level,
			];
            
            $url = $this->trailingslashit( LOGSTREAM_API_URL ) . LOGSTREAM_API_TOKEN;
			
			$this->post( $url, $params );
		}
        
        /**
         * Appends a trailing slash.
         *
         * @param string $value Value to which trailing slash will be added.
         * @return string String with trailing slash added.
         */
        protected function trailingslashit( string $value ): string {
        	return $this->untrailingslashit( $value ) . '/';
        }
        
        /**
         * Removes trailing forward slashes and backslashes if they exist.
         *
         * @param string $text Value from which trailing slashes will be removed.
         * @return string String without the trailing slashes.
         */
        protected function untrailingslashit( string $value ): string {
        	return rtrim( $value, '/\\' );
        }
        
		/**
		 * Sends a POST request.
		 *
         * @param string $url               The request url.
         * @param array  $params            The request parameters.
         * @param bool   $raw_post_fields   Whether to send $params as raw fields.
         * @param array  $headers           The request headers.
		 * @return  string
		 */
		protected function post( string $url, array $params = [], bool $raw_post_fields = false, array $headers = [] ): string {
			
            $curl = curl_init();
		    $opts = array(
				CURLOPT_URL 			=> $url,
				CURLOPT_RETURNTRANSFER 	=> true,
				CURLOPT_ENCODING 		=> "",
				CURLOPT_MAXREDIRS 		=> 10,
				CURLOPT_TIMEOUT 		=> 0,
				CURLOPT_FOLLOWLOCATION 	=> false,
				CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST 	=> 'POST',
				CURLOPT_SSL_VERIFYHOST	=> false,
				CURLOPT_SSL_VERIFYPEER	=> false,
                CURLOPT_POSTFIELDS      => $raw_post_fields ? $params : http_build_query( $params ),
			);
		    
            $opts[ CURLOPT_HTTPHEADER ] = $this->get_headers( $headers );
		    
			curl_setopt_array( $curl, $opts );
		    
			$response = curl_exec( $curl );
			$httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
			$error	  = curl_error( $curl );
            
			curl_close( $curl );
		    
			if ( ! empty( $error ) ) {
				throw new \Exception( $error );
			}
			
			return $response;
	    }
        
        /**
         * Generates a headers array.
         *
         * @param array $atts   The header attributes.
         * @return array  The headers array.
         */
    	protected function get_headers( array $atts = [] ): array {
		    
            $defaults = [
				"User-Agent" 		=> "Utils/0.1",
			    "Accept"	 		=> "application/json, text/plain, */*",
				"Cache-Control" 	=> "no-cache",
				"Referer" 			=> "",
				"Accept-Language" 	=> "de,en-US;q=0.7,en;q=0.3",
			    "Accept-Encoding" 	=> "gzip, deflate",
			    "Connection" 		=> "keep-alive"
    		];
            
    		// sets default header values if not given
    		$headers = $addheaders = [];
    	    foreach ( $defaults as $name => $default ) {
    	        if ( array_key_exists( $name, $atts ) ) {
    	            $headers[] = $name . ": " . $atts[ $name ];
    	        } else {
    	            $headers[] = $name . ": " . $default;
    	        }
    	    }
    	    foreach ( $atts as $name => $att ) {
    			$header = $name . ": " . $atts[ $name ];
    			if ( !in_array( $header, $headers ) ) {
    				$headers[] = $header;
    			}
    	    }
            
    		return $headers;
    	}
		
	}
	
}
