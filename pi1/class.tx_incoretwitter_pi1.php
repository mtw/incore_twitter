<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 incore IT Solutions <office@incore.at>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'jQuery Twitter Feed' for the 'incore_twitter' extension.
 *
 * @author	incore IT Solutions <office@incore.at>
 * @package	TYPO3
 * @subpackage	tx_incoretwitter
 */
class tx_incoretwitter_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_incoretwitter_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_incoretwitter_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'incore_twitter';	// The extension key.
	var $pi_checkCHash = true;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->init();
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		$this->lConf;

		if ($GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] != '') {
			return '<strong>This Plugin can only be used once per page!</strong>';
		}
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] = '
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
			<script type="text/javascript" src="typo3conf/ext/incore_twitter/js/jScrollPane/jquery.mousewheel.js"></script>
			<script type="text/javascript" src="typo3conf/ext/incore_twitter/js/jScrollPane/jquery.jscrollpane.min.js"></script>		
			<script type="text/javascript" src="typo3conf/ext/incore_twitter/js/jQueryTwitter.js"></script>
		';
		$tweetUsersArray = t3lib_div::trimExplode(',',$this->lConf['twitteraccounts']);
		$hashtagsArray = t3lib_div::trimExplode(',', $this->lConf['hashtags']);
		$tweetUsers = "['" . implode("', '", $tweetUsersArray) . "']";
		$hashtags = "['" . implode("', '", $hashtagsArray) . "']";
		$uid = $this->cObj->data['uid'];
		$tweetLimit = $this->lConf['twittercounts'];
		if ($tweetLimit == 0) { $tweetLimit = 10;}
		
		$content = '
			<script type="text/javascript">
			var tweetUsers = ' . $tweetUsers . ';
			var hashTags = ' . $hashtags . ';
			var tweetsCount = ' . $tweetLimit . ';
			var buildString = "";
			var hashTagString = "";
			var URI = "";
			var containerId = \'#tweet-container-' . $uid . '\';
			$(document).ready(function(){	
				for(var i=0;i<tweetUsers.length;i++) {
					if(i!=0) buildString+=\'+OR+\';
					buildString+=\'from:\'+tweetUsers[i];
				}
				for(var j=0;j<hashTags.length;j++) {
					if(j!=0) hashTagString+=\'+OR+\';
					hashTagString+=\'%23\'+hashTags[j];
				}
				URI = "http://search.twitter.com/search.json?q=" + hashTagString + "+" + buildString + "&callback=TweetTick&rpp=" +tweetsCount;
				var fileref = document.createElement(\'script\');
				fileref.setAttribute("type","text/javascript");
				fileref.setAttribute("src", URI);
				document.getElementsByTagName("head")[0].appendChild(fileref);
			});
			</script>
			<div id="tweet-container-'.$uid.'" class="tweet-container">Loading..</div>
			<div id="scroll"></div>
			';
		return $this->pi_wrapInBaseClass($content);
	}

	function init(){
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
		$this->lConf = array(); // Setup our storage array...
		// Assign the flexform data to a local variable for easier access
		$piFlexForm = $this->cObj->data['pi_flexform'];
		// Traverse the entire array based on the language...
		// and assign each configuration option to $this->lConf array...
		foreach ($piFlexForm['data'] as $sheet => $data ) {
			foreach ( $data as $lang => $value ) {
				foreach ( $value as $key => $val ) {
					$this->lConf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
				}
			}
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/incore_twitter/pi1/class.tx_incoretwitter_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/incore_twitter/pi1/class.tx_incoretwitter_pi1.php']);
}

?>
