<?php

//
// Open Web Analytics - An Open Source Web Analytics Framework
//
// Copyright 2006 Peter Adams. All rights reserved.
//
// Licensed under GPL v2.0 http://www.gnu.org/copyleft/gpl.html
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// $Id$
//

/**
 * Bar Graph of Page Views
 * 
 * @author      Peter Adams <peter@openwebanalytics.com>
 * @copyright   Copyright &copy; 2006 Peter Adams <peter@openwebanalytics.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GPL v2.0
 * @category    owa
 * @package     owa
 * @version		$Revision$	      
 * @since		owa 1.0.0
 */
class owa_graph_page_views extends owa_graph {	

	/**
	 * Constructor
	 *
	 * @return owa_graph_page_views
	 */
	function owa_graph_page_views() {
	
		$this->owa_graph();
		$this->api_calls = array('page_views', 'swf_pv', 'pv_visits');
	
		return;
	}

	/**
	 * Generate Graph
	 *
	 * @param 	array $params
	 * @access 	public
	 * @return 	array
	 */
	function generate($params) {
			
		$this->params = $params;
	
		switch ($params['api_call']) {
		
			case "page_views":
				return $this->graph_page_views();
			case "pv_visits":
				return $this->pv_visits();

		}
		
		return;
	}
	
	/**
	 * Graphs Page Views
	 *
	 */
	function graph_page_views() {
		
		switch ($this->params['period']) {
		
			case "this_year":
			
				$result = $this->metrics->get(array(
		
					'api_call' 		=> 'page_views',
					'period'			=> $this->params['period'],
					'result_format'		=> 'inverted_array',
					'constraints'		=> array(
						
						'is_browser' => 1,
						'is_robot' 	=> 0),
					'group_by'			=> 'month'
					));
					
				
				$date = array();
				
				foreach ($result['month'] as $key => $value) {
					
					$date[$key] = $this->get_month_label($value);
				}
				
				$this->data = array(
		
					'datay'		=> $result['page_views'],
					'datax'		=> $date	);
					
					
				$this->params['xaxis_title'] = "Month";
				
				break;
			
			default:
			
				$result = $this->metrics->get(array(
		
					'api_call' 		=> 'page_views',
					'period'			=> $this->params['period'],
					'result_format'		=> 'inverted_array',
					'constraints'		=> array(
						
						'is_browser' => 1,
						'is_robot' 	=> 0),
					'group_by'			=> 'day',
					'order'				=> 'ASC'
					));
					
				$date = $this->make_date_label($result['day'], $result['month']);
				
				$this->data = array(
		
					'datay'		=> $result['page_views'],
					'datax'		=> $date);
			
				$this->params['xaxis_title'] = "";
				
				break;
		}
		
		
		$this->params['graph_title'] = "Page views for " . $this->get_period_label($this->params['period']);
		
		$this->params['yaxis_title'] = "Page views";
		
		$this->params['width'] = 700;
		$this->params['height'] = 200;
		
		$this->graph($this->params['type']);
		
		return;
	}
	
	function pv_visits() {
		
		$result = $this->metrics->get(array(
		
					'api_call' 		=> 'dash_core',
					'period'			=> $this->params['period'],
					'result_format'		=> 'inverted_array',
					'constraints'		=> array(
						
						'is_browser' => 1,
						'is_robot' 	=> 0),
					'group_by'			=> 'day',
					'order'				=> 'ASC'
					));
					
		$date = $this->make_date_label($result['day'], $result['month']);
				
		$this->data = array(
							'y2'	=> $result['page_views'],
							'y1'	=> $result['sessions'],
							'x'		=> $date
						);
		
	
		$this->params['width'] = 900;
		$this->params['height'] = 200;
		$this->params['graph_title'] = "Page Views & Visits for " . $this->get_period_label($this->params['period']);
		$this->params['y2_title'] = "PageViews";
		$this->params['y1_title'] = "Visits";
		
		$this->graph($this->params['type']);
		
		return;
	}
	
}

?>