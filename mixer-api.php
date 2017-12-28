<?php
/*
	Mixer API Wrapper
	----------------------------------------------------------------
	License:	GPL v3
	Created:	27DEC2017
	Updated:	NULL
	Desc.:		Wrapper for the Mixer.com (Formerly Beam.pro) API
	Demo:		http://phux.org/mixer
	Usage:		$var = new Mixer('MixerName')
				ie.
				$stream = new Mixer('Neat0');
				echo $stream->userName.' is playing '.$this->game;
				
	TO-DO:		Finish adding advanced stream information
				Add chat content
*/
class Mixer {
	public $mixer_name;
	function __construct($mixer_name) {
		$this->user = $mixer_name;
		if(!empty($this->user) AND $this->user != '') {
			$this->user = $mixer_name;
			$this->retrieveChannel();
		} else {
			$this->mixererr			= 1; // Return 'Streamer Name Empty' error
			return $this->mixererr;
		}
	}
	function retrieveChannel() {
		$streamurl = 'https://mixer.com/api/v1/channels/'.$this->user;
		if('' == $streamcontent = file_get_contents($streamurl)) {
			// Streamer doesn't exist
			$this->mixererr			= 2; // Return 'Streamer Not Found' error
			return $this->mixererr;
		} else {
			$streamjson 			= json_decode($streamcontent, true);
			// Streamer Info
			$this->MixerID			= $streamjson['id'];
			$this->userName			= $streamjson['token'];
			$this->offlineImage		= $streamjson['bannerUrl'];
			$this->avatar			= $streamjson['user']['avatarUrl'];
			$this->profile			= $streamjson['description'];
			$this->bio				= $streamjson['user']['bio'];
			$this->facebook			= $streamjson['user']['social']['facebook'];
			$this->twitter			= $streamjson['user']['social']['twitter'];
			$this->youtube			= $streamjson['user']['social']['youtube'];
			
			// Stream Info
			$this->status 			= $streamjson['online'] == true ? 'Online' : 'Offline';
			$this->stream			= $streamjson['hosteeId'] == true ? $streamjson['hosteeId'] : $this->MixerID;
			$this->title			= $streamjson['name'];
			$this->game				= $streamjson['type']['name'];
			
			// Viewers
			$this->totalViews		= $streamjson['viewersTotal'];
			$this->currViewers		= $streamjson['viewersCurrent'];
			$this->followers		= $streamjson['numFollowers'];
			
			// Misc.
			$this->audience			= $streamjson['audience'];
			$this->sparks			= $streamjson['user']['sparks'];
			$this->level			= $streamjson['user']['level'];
			
			// Chat Stuff [TO-DO]
			$chaturl				= 'https://mixer.com/api/v1/chats/'.$this->MixerID.'/history';
			$chatcontent			= file_get_contents($chaturl);
			$chatjson				= json_decode($chatcontent, true);
		}
	}
}
?>