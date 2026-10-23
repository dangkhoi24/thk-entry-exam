<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <x-templates.default-page :title="__('admin.top.title')">
        <x-card>
            <p class="body-1 text-secondary">{!! __('admin.top.description') !!}</p>
        </x-card>
    </x-templates.default-page>
@endsection
