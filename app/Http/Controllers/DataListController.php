<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class DataListController extends Controller
{
    public function index(Client $client)
    {

    	$licitacoes = [];
		$hasLink = true;
		$qtd_pagina = 10;
		$pagina = 1;

		$client->setClient(new GuzzleClient(array(
		    'timeout' => 15,
		));
		$crawler = $client->request('GET', 'http://www.cnpq.br/web/guest/licitacoes');

		do {

			$crawler->filter('.resultado-licitacao > table > tbody.table-data > tr > td')->each(function ($node) use (&$licitacoes) {
				$attachments = [];

				$node->filter('.download-list > li > a')->each(function($linkNode, $key) use (&$attachments) {
					$attachments[$key] = (object)[
						'text' => $linkNode->text(),
						'link' => $linkNode->link()->getUri(),
					];
				});

				$licitacoes[] = (object) [
					'name'           => $node->filter('.titLicitacao')->text(),
					'origin' 		 => $node->getBaseHref(),
					'object'         => $node->filter('.cont_licitacoes')->html(),
					'data_licitacao' => $node->filter('.data_licitacao')->html(),
					'starting_date'  => $node->filter('.data_licitacao > span')->html(),
					'attachments'	 => $attachments,
				];
			});

			$link = $crawler->filter('.taglib-page-iterator')->selectLink('Próximo');
			if(empty($link->getNode(0))){
				$hasLink = false;
				continue;
			}

			$pagina = preg_replace('/\D/', '', $link->getNode(0)->getAttribute('onclick'));

			try {
				$crawler = $client->request('GET', 'http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina='.$pagina.'&delta='. $qtd_pagina .'&registros=1492');
			} catch (\Exception $e) {
				$hasLink = false;
			}

		} while($hasLink);

    	return view('listagem', compact(['licitacoes']));
    }
}
