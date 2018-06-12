@extends('layout.master')
@section('master-content')
<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
	<div class="lh-100">
		<h3 class="mb-0 text-white lh-100">Licitações</h3>
		<small>Pequeno exemplo de uma listagem coletada da web</small>
	</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">
	@forelse($licitacoes as $licitacao)
	<div class="media text-muted pt-3">
		<img data-src="holder.js/32x32?theme=thumb&bg=007bff&fg=007bff&size=1" alt="" class="mr-2 rounded">
		<div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
			<div class="d-flex justify-content-between align-items-center w-100">
				<strong class="text-gray-dark">{{ $licitacao->name }}</strong>
				<a class="small" href="{{ $licitacao->origin }}">Fonte</a>
			</div>
			<span class="d-block">
				Data Abertura:
				<strong class="text-gray-dark">{{ $licitacao->starting_date }}</strong>
			</span>
			{!! $licitacao->object !!}
			<p class="media-body pb-3 mb-0 border-gray">
				<strong class="d-block text-gray-dark">Anexos: </strong>
				<ul class="">
					@foreach($licitacao->attachments as $attachment)
					<li>
						<a href="{{ $attachment->link }}">{{ $attachment->text }}</a>
					</li>
					@endforeach
				</ul>
			</p>

		</div>
	</div>
	@empty
	<div class="media text-muted pt-3">
		<p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray text-center">
			Nenhuma licitação a exibir
		</p>
	</div>
	@endforelse
</div>
@endsection