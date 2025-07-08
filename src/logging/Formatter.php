<?php

namespace msltns\logging;

use Monolog\Formatter\FormatterInterface;

/**
 * Formats a given debug log output.
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
if ( ! class_exists( '\msltns\logging\Formatter' ) ) {
	
	class Formatter implements FormatterInterface {
	
		private $name = '';
	
	    /**
	     * Main constructor.
	     *
	     * @param  string $name The application name
	     * @return void
	     */
		public function __construct( $name ) {
			$this->name = $name;
		}
	
        /**
         * Formats a log record.
         *
         * @param  array    $record A record to format
         * @return mixed    The formatted record
         */
	    public function format( array $record ) {
			$datetime 	= $record['datetime'];
			$datetime 	= $datetime->format( 'd-M-Y H:i:s' );
			$level 		= str_replace( "{$this->name}.", "", $record['level_name'] );
			$context	= '';
			if ( ! empty( $record['context'] ) ) {
				$context = ', context: ' . json_encode( $context );
			}
		
	        return '[' . $datetime . ' UTC]' . ' ' . $level . ':	' .	$record['message'] . $context . PHP_EOL;
	    }

        /**
         * Formats a set of log records.
         *
         * @param  array<LogRecord> $records A set of records to format
         * @return mixed            The formatted set of records
         */
	    public function formatBatch( array $records ) {
			$result = [];
	    	foreach( $records as $record ) {
	    		$result[] = $this->format( $record );
	    	}
		
			return $result;
	    }
	
	}
}
