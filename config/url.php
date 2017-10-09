<?php

$url['production'] = function(){
	return in_array($this->url->host, ['neofrag.download', 'neofr.ag']);
};

//TODO 0.1.7
$url['domains'] = [
	'neofr.ag'         => function(){

	},
	'neofrag.download' => function(){

	},
	'neofrag'          => function(){
		return '';
	}
];
