<?php

namespace msltns\logging;

use msltns\logging\Logstream_Handler;

/**
 * Class Grafana_Loki_Handler provides several useful logging methods.
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

if ( ! class_exists( '\msltns\logging\Grafana_Loki_Handler' ) ) {
	
	class Grafana_Loki_Handler extends Logstream_Handler {
		
		/**
         * Main constructor.
         */
		private function __construct() {
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
            
            $stream = [
                "platform"      => $this->platform,
                "environment"   => $this->environment,
                "service_name"  => $this->service,
                "level"         => $level,
            ];
            
            $_nanosec = date('Uu').'000';
            
            $entry = [];
            if ( ! empty( $context ) ) {
                $entry = [ $_nanosec, $message, $context ];
            } else {
                $entry = [ $_nanosec, $message ];
            }
            
            $values[] = $entry;
            
            $data = [
                "streams" => [
                    [
                        "stream" => $stream,
                        "values" => $values
                    ],
                ],
            ];
            
            $data = json_encode( $data );
            
            $headers = [
                "Content-Type"  => "application/json",
                "Authorization" => "Bearer " . LOGSTREAM_API_TOKEN,
            ];
            
            $url = $this->untrailingslashit( LOGSTREAM_API_URL ) . '/loki/api/v1/push';
            
            $response = $this->post( $url, $data, true, $headers );
            
		}
		
	}
	
}
