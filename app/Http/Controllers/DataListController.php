<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;

class DataListController extends Controller
{
    public function index(Client $client)
    {

    	$licitacoes = [];
		$crawler = $client->request('GET', 'http://www.cnpq.br/web/guest/licitacoes');

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

    	return view('listagem', compact(['licitacoes']));
    }
}
